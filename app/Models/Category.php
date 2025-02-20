<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
        'description'
    ];
    protected $appends = ['full_image_path'];

    public function getFullImagePathAttribute()
    {
        return $this->attributes['image_path'] ? asset('storage/image/categories/' . $this->attributes['image_path']) : 'https://placehold.co/600x400';
    }

    public function products()
    {
        return $this->hasMany(Product::class);
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
