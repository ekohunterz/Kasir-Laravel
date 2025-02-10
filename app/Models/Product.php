<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'is_active',
        'image_path',
        'category_id',
    ];

    protected $appends = [
        'formated_price',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function getStockAttribute($value)
    {
        return $value > 0 ? $value : 'Out of stock';
    }

    public function getFormatedPriceAttribute()
    {
        return 'Rp. ' . number_format($this->price, 0, ',', '.');
    }

    public function getImagePathAttribute($value)
    {
        return $value ? asset('storage/' . $value) : 'https://placehold.co/600x400';
    }
}
