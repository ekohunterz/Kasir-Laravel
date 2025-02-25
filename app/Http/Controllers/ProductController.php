<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Imports\ProductsImport;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product create', ['only' => ['create', 'store', 'template', 'import']]);
        $this->middleware('permission:product read', ['only' => ['index', 'show']]);
        $this->middleware('permission:product update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product delete', ['only' => ['destroy', 'destroyBulk']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::query();

        if ($request->has('search')) {
            $search = $request->search; // Store search term in a variable

            $products->where(function ($query) use ($search) { // Group OR conditions
                $query->where('name', 'LIKE', "%" . $search . "%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'LIKE', "%" . $search . "%");
                    });
            });
        }

        if ($request->has(['field', 'order'])) {
            $products->orderBy($request->field, $request->order);
        }

        $perPage = $request->has('perPage') ? $request->perPage : 10;


        return Inertia::render('Product/Index', [
            'title' => __('app.label.product'),
            'filters' => $request->all(['search', 'field', 'order']),
            'perPage' => (int)$perPage,
            'products' => $products->with('category')->orderBy('created_at', 'desc')->paginate($perPage)->onEachSide(0),
            'breadcrumbs' => [
                ['label' => __('app.label.product'), 'href' => route('product.index')]
            ],
            'categories' => Category::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function import(Request $request)
    {
        try {
            $request->validate(['file' => 'required|mimes:csv,xls,xlsx']);

            $file = $request->file('file');

            $file_name = rand() . $file->getClientOriginalName();

            Storage::put('public/import/' . $file_name, File::get($file), 'public');

            Excel::import(new ProductsImport, 'public/import/' . $file_name);

            // Hapus file setelah impor selesai
            Storage::delete('public/import/' . $file_name);

            return back()->with('success', 'Products imported successfully.');
        } catch (ValidationException $ve) {

            return back()->with('error', $ve->failures());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
                'is_active' => $request->is_active,
            ]);

            if ($request->image != null) {
                $image = $request->image;
                $imageName = time() . '.' . $image->extension();
                Storage::put('public/image/products/' . $imageName, File::get($image), 'public');
                $product->update([
                    'image_path' => $imageName
                ]);
            }

            DB::commit();
            return back()->with('success', __('app.label.created_successfully', ['name' => $product->name]));
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.product')]) . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function template()
    {

        return Storage::download('public/template/template.csv');
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $product->update([
                'name'          => $request->name,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock,
                'is_active'     => $request->is_active,
                'category_id'   => $request->category_id
            ]);

            if ($request->image != null) {
                Storage::delete('public/image/products/' . $product->image_path);
                $image = time() . "." . $request->image->extension();
                Storage::put('public/image/products/' . $image, File::get($request->image), 'public');
                $product->update([
                    'image_path' => $image
                ]);
            }

            DB::commit();
            return back()->with('success', __('app.label.updated_successfully', ['name' => $product->name]));
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.updated_error', ['name' => $product->name]) . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            Storage::delete('public/image/categories/' . $product->image_path);
            $product->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $product->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $product->name]) . $th->getMessage());
        }
    }

    public function destroyBulk(Request $request)
    {
        try {
            $products = Product::whereIn('id', $request->id)->get();

            foreach ($products as $product) {
                Storage::delete('public/image/products/' . $product->image_path);
            }
            Product::whereIn('id', $request->id)->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => count($request->id) . ' ' . __('app.label.prod$product')]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => count($request->id) . ' ' . __('app.label.prod$product')]) . $th->getMessage());
        }
    }
}
