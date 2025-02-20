<?php

namespace App\Models;

use Carbon\Carbon;
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
        'full_image_path'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function getFormatedPriceAttribute()
    {
        return 'Rp. ' . number_format($this->price, 0, ',', '.');
    }

    public function getFullImagePathAttribute()
    {
        return $this->attributes['image_path'] ? asset('storage/image/products/' . $this->attributes['image_path']) : 'https://placehold.co/600x400';
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->isoFormat('D MMMM Y HH:mm');
    }

    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->isoFormat('D MMMM Y HH:mm');
    }
}
