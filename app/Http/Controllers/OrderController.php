<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:order create');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // Get Products
        $products = Product::query()->where('is_active', 1);

        if ($request->has('search')) {
            $products->where('name', 'LIKE', "%" . $request->search . "%");
        }

        if ($request->has(['field', 'order'])) {
            $products->orderBy($request->field, $request->order);
        }

        $perPage = $request->has('perPage') ? $request->perPage : 12;

        $carts = Cart::with('product:id,name,price,image_path')->where('cashier_id', auth()->id())->get();

        $subtotal = Cart::where('cashier_id', auth()->id())->sum('price');

        return Inertia::render('Order/Index', [
            'title' => 'Orders',
            'products' => $products->paginate($perPage)->onEachSide(0),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'carts' => $carts,
            'subTotal' => (int) $subtotal,
            'customers' => Customer::all(),
            'payments' => Payment::all(),
        ]);
    }

    /**
     * Add to cart.
     */
    public function addToCart(CartStoreRequest $request)
    {

        DB::beginTransaction();
        try {
            $product = Product::find($request->product_id); // Use find for simpler lookup

            if (!$product) {
                return back()->with('error', 'Product not found.'); // Handle product not found
            }

            $cart = Cart::with('product')
                ->where('product_id', $request->product_id)
                ->where('cashier_id', auth()->id())
                ->first();

            // Check stock *before* any cart operations
            if ($product->stock <= 0 || ($cart && $product->stock <= $cart->quantity)) { // Check if stock is 0 or less than or equal to current quantity
                return back()->with('error', 'Out of Stock Product!.'); // Return error immediately
            }

            if ($cart) {
                // Update existing cart item
                $cart->quantity++;
                $cart->price = $cart->quantity * $product->price;
                $cart->save();
            } else {
                // Create new cart item
                Cart::create([
                    'cashier_id' => auth()->id(),
                    'product_id' => $request->product_id,
                    'quantity' => 1,
                    'price' => $product->price,
                ]);
            }

            DB::commit();

            return back(); // Return 

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage()); // Return error message
        }
    }

    public function clearCart()
    {
        DB::beginTransaction();

        try {
            Cart::where('cashier_id', auth()->id())
                ->delete();
            DB::commit();

            return back()->with('success', 'Product removed from cart successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function editQuantity(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $cart = Cart::with('product')->where('id', $id)->first(); // Eager load product

            if (!$cart) {
                return back()->with('error', 'Cart item not found.'); // Handle cart not found
            }

            if ($request->type == 'increment') {
                if ($cart->product->stock <= $cart->quantity) { // Corrected stock check
                    return back()->with('error', 'Out of Stock Product!.');
                }
                $cart->increment('quantity');
                $cart->update([
                    'price' => $cart->quantity * $cart->product->price
                ]);
            } elseif ($request->type == 'decrement') { // Explicitly check for decrement
                if ($cart->quantity > 1) {
                    $cart->decrement('quantity');
                    $cart->update([
                        'price' => $cart->quantity * $cart->product->price
                    ]);
                } else {
                    $cart->delete();
                }
            } else {
                return back()->with('error', 'Invalid request type.'); // Handle invalid request type
            }

            DB::commit();

            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage()); // Log the exception for debugging
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $carts = Cart::where('cashier_id', auth()->id())->get();
            $subtotal = Cart::where('cashier_id', auth()->id())->sum('price');

            $order = Order::create([
                'cashier_id' => auth()->id(),
                'customer_id' => $request->customer_id,
                'invoice_number' => 'INV/' . time(),
                'sub_total' => $subtotal,
                'grand_total' => $request->grand_total,
                'cash' => $request->cash,
                'change' => $request->cash - $request->grand_total,
                'discount' => $request->discount ?? 0,
                'payment_id' => $request->payment_id,
                'already_paid' => $request->already_paid,
                'note' => $request->note,
            ]);

            $order->orderDetails()->createMany($carts->map(function ($cart) {
                return [
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->price,
                ];
            })->toArray());

            // Update stock product
            foreach ($carts as $cart) {
                $product = Product::whereId($cart->product_id)->first();
                $product->update([
                    'stock' => $product->stock - $cart->quantity
                ]);
            }

            // Clear cart
            Cart::where('cashier_id', auth()->id())->delete();
            DB::commit();
            return back()->with('success', 'Order created successfully')->with('data', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function print($order_id)
    {
        $order = Order::with(['orderDetails.product', 'customer'])->where('id', $order_id)->firstOrFail();
        $setting = Setting::first();

        return view('invoice.print', [
            'order' => $order,
            'setting' => $setting,
        ]);
    }
}
