<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::query();
        if ($request->has('search')) {
            $categories->where('name', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $categories->orderBy($request->field, $request->order);
        }
        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render('Category/Index', [
            'title'         => __('app.label.category'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'categories'   => $categories->orderBy('created_at', 'desc')->paginate($perPage)->onEachSide(0),
            'breadcrumbs'   => [['label' => __('app.label.category'), 'href' => route('category.index')]],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {

        DB::beginTransaction();
        try {
            $category = Category::create([
                'name'          => $request->name,
                'description'   => $request->description,
            ]);

            if ($request->image != null) {
                $image = $request->image;
                $imageName = time() . '.' . $image->extension();
                Storage::put('public/image/categories/' . $imageName, File::get($image), 'public');
                $category->update([
                    'image_path' => $imageName
                ]);
            }

            DB::commit();
            return back()->with('success', __('app.label.created_successfully', ['name' => $category->name]));
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.category')]) . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {

        DB::beginTransaction();
        try {
            $category->update([
                'name'          => $request->name,
                'description'   => $request->description,
            ]);

            if ($request->image != null) {
                Storage::delete('public/image/categories/' . $category->image_path);
                $image = time() . "." . $request->image->extension();
                Storage::put('public/image/categories/' . $image, File::get($request->image), 'public');
                $category->update([
                    'image_path' => $image
                ]);
            }

            DB::commit();
            return back()->with('success', __('app.label.updated_successfully', ['name' => $category->name]));
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.updated_error', ['name' => $category->name]) . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {

        try {
            Storage::delete('public/image/categories/' . $category->image_path);
            $category->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $category->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $category->name]) . $th->getMessage());
        }
    }

    public function destroyBulk(Request $request)
    {
        try {
            $categories = Category::whereIn('id', $request->id)->get();

            foreach ($categories as $category) {
                Storage::delete('public/image/categories/' . $category->image_path);
            }
            Category::whereIn('id', $request->id)->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => count($request->id) . ' ' . __('app.label.category')]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => count($request->id) . ' ' . __('app.label.category')]) . $th->getMessage());
        }
    }
}
