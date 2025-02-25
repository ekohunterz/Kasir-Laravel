<?php

namespace App\Http\Controllers;

use App\Exports\IncomeExport;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:invoice read', ['only' => ['index', 'export']]);
        $this->middleware('permission:invoice delete', ['only' => ['destroy', 'destroyBulk']]);
    }

    public function index(Request $request)
    {
        $transactionsQuery = Order::query(); // Base query for transactions (used twice)

        $transactionsQuery->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select('orders.*', 'customers.name as customer_name');

        if ($request->has('search')) {
            $search = $request->search;
            $transactionsQuery->where(function ($query) use ($search) {
                $query->whereAny([
                    'orders.invoice_number',
                    'customers.name'
                ], 'LIKE', "%" . $search . "%");
            });
        }

        if ($request->has(['date_start', 'date_end'])) {
            $transactionsQuery->whereBetween('orders.created_at', [$request->date_start, $request->date_end]);
        }

        if ($request->has(['field', 'order'])) {
            $transactionsQuery->orderBy($request->field, $request->order);
        }


        // 1. Calculate Total Income (Correctly)
        $totalIncome = $transactionsQuery->sum('grand_total');  // Execute query before sum

        // 2. Fetch Transactions (Using the same query with pagination)
        $perPage = $request->input('perPage', 10);
        $transactions = $transactionsQuery->with(['payment:id,name', 'user:id,name'])->orderBy('created_at', 'desc')->get();

        // 3. Calculate Products Sold (After getting the orders)
        $productsSold = 0;
        foreach ($transactions as $transaction) {
            $productsSold += $transaction->orderDetails->sum('quantity');
        }


        return Inertia::render('Income/Index', [
            'title' => 'Income',
            'transactions' => $transactionsQuery->paginate($perPage)
                ->onEachSide(0),
            'filters' => $request->all(['search', 'field', 'order', 'date_start', 'date_end', 'perPage']),
            'perPage' => (int)$perPage,
            'total_income' => (int) $totalIncome,
            'total_transaction' => $transactions->count(),
            'products_sold' => $productsSold,
        ]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $order = Order::find($id);

            $order->delete();
            DB::commit();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $order->invoice_number]));
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.deleted_error',  $th->getMessage()));
        }
    }

    public function destroyBulk(Request $request)
    {
        try {
            $orders = order::whereIn('id', $request->id);
            $orders->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => count($request->id) . ' ' . __('app.label.order')]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => count($request->id) . ' ' . __('app.label.order')]) . $th->getMessage());
        }
    }

    public function export(Request $request)
    {

        return Excel::download(new IncomeExport($request->date_start, $request->date_end), 'income-' . $request->date_start . '-' . $request->date_end . '.xlsx');
    }
}
