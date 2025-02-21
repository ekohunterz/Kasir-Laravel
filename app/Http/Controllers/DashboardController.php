<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {

        $products_sold_today = OrderDetail::whereDate('created_at', Carbon::now())
            ->sum('quantity');

        $products_sold_yesterday = OrderDetail::whereDate('created_at', Carbon::now()->subDay())
            ->sum('quantity');

        $percentage_change = 0;
        if ($products_sold_yesterday > 0) {
            $percentage_change = (($products_sold_today - $products_sold_yesterday) / $products_sold_yesterday) * 100;
        }

        $income_today = Order::whereDate('created_at', Carbon::now())
            ->sum('grand_total');

        $income_yesterday = Order::whereDate('created_at', Carbon::now()->subDay())
            ->sum('grand_total');

        $diff = $income_today - $income_yesterday;
        $percentage_change_income = 0;
        if ($income_yesterday > 0) {
            $percentage_change_income = ($diff / $income_yesterday) * 100;
        }

        $transactions_today = Order::whereDate('created_at', Carbon::now())
            ->count();

        $transactions_yesterday = Order::whereDate('created_at', Carbon::now()->subDay())
            ->count();

        $percentage_change_transaction = 0;
        if ($transactions_yesterday > 0) {
            $percentage_change_transaction = (($transactions_today - $transactions_yesterday) / $transactions_yesterday) * 100;
        }

        $top_selling_products = OrderDetail::with('product:id,name,image_path')->selectRaw('product_id, sum(quantity) as total_quantity')
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->get();

        $top_selling_products_formatted = [];
        foreach ($top_selling_products as $product) {
            $top_selling_products_formatted[] = [
                'name' => $product->product->name,
                'quantity' => $product->total_quantity,
                'image' => $product->product->full_image_path,
            ];
        }

        $products_stock = Product::select('id', 'name', 'stock', 'image_path')->orderBy('stock', 'asc')->take(5)->get();

        $products_stock_formatted = [];
        foreach ($products_stock as $product) {
            $products_stock_formatted[] = [
                'name' => $product->name,
                'quantity' => $product->stock,
                'image' => $product->full_image_path,
            ];
        }

        return Inertia::render('Dashboard', [
            'userCount'         => User::count(),
            'roleCount'         => Role::count(),
            'permissionCount'   => Permission::count(),
            'productsSold' => [
                'today' => $products_sold_today,
                'yesterday' => $products_sold_yesterday,
                'percentage_change' => round($percentage_change, 2),
                'status' => $products_sold_today > $products_sold_yesterday ? 'success' : 'danger',
            ],
            'income' => [
                'today' => $income_today,
                'yesterday' => $income_yesterday,
                'percentage_change' => round($percentage_change_income, 2),
                'status' => $income_today > $income_yesterday ? 'success' : 'danger',
            ],
            'transactions' => [
                'today' => $transactions_today,
                'yesterday' => $transactions_yesterday,
                'percentage_change' => round($percentage_change_transaction, 2),
                'status' => $transactions_today > $transactions_yesterday ? 'success' : 'danger',
            ],
            'topSellingProducts' => $top_selling_products_formatted,
            'productsStock' => $products_stock_formatted,
        ]);
    }
}
