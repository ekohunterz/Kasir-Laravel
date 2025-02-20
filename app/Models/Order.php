<?php

namespace App\Models;

use Carbon\Carbon;
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
        'already_paid',
        'note',
    ];

    protected $appends = [
        'tax',
        'formated_sub_total',
        'formated_discount',

    ];


    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getGrandTotalAttribute($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }

    public function getCashAttribute($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }

    public function getChangeAttribute($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }

    public function getFormatedSubTotalAttribute()
    {
        return 'Rp. ' . number_format($this->sub_total, 0, ',', '.');
    }

    public function getFormatedDiscountAttribute()
    {
        return 'Rp. ' . number_format($this->discount, 0, ',', '.');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getTaxAttribute()
    {
        $tax = ($this->sub_total + $this->discount) * 11 / 100;
        return  'Rp. ' . number_format($tax, 0, ',', '.');
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->isoFormat('D MMMM Y HH:mm');
    }
}
