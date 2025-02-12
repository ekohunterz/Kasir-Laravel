<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Order::query();

        $transactions->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select('orders.*', 'customers.name as customer_name');


        if ($request->has('search')) {
            $transactions->where('customers.name', 'LIKE', "%" . $request->search . "%");
            $transactions->orWhere('orders.invoice_number', 'LIKE', "%" . $request->search . "%");
        }

        if ($request->has(['field', 'order'])) {
            $transactions->orderBy($request->field, $request->order);
        }

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        return Inertia::render('Income/Index', [
            'title' => 'Income',
            'transactions' => $transactions->paginate($perPage)->onEachSide(0),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage

        ]);
    }
}
