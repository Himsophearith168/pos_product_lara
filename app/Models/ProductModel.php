<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'product';
    protected $fillable = ['name', 'description', 'price', 'quantity', 'image', 'category_id'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
