<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IncomeExport implements FromView
{
    protected $date_start;
    protected $date_end;

    public function __construct($date_start, $date_end)
    {
        $this->date_start = $date_start;
        $this->date_end = $date_end;
    }

    public function view(): View
    {
        $income = Order::with(['customer', 'user']);
        if ($this->date_start && $this->date_end) {
            $income->whereBetween('created_at', [$this->date_start, $this->date_end]);
        }

        return view('exports.income', [
            'income' => $income->get(),
            'total_income' => $income->sum('grand_total'),
        ]);
    }
}
