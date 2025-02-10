<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'cashier_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $appends = [
        'formated_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormatedPriceAttribute()
    {
        return 'Rp. ' . number_format($this->price, 0, ',', '.');
    }
}
