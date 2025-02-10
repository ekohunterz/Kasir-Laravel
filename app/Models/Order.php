<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'cashier_id',
        'customer_id',
        'invoice_number',
        'grand_total',
        'sub_total',
        'cash',
        'change',
        'discount',
        'payment_id',
        'paid_status',
        'note',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getTotalPriceAttribute($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }

    public function getPaidNominalAttribute($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
