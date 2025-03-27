<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'sku', 'type', 'short_description', 'description', 'on_sale', 'price', 'regular_price', 'sale_price', 'currency_code', 'stock_quantity', 'is_in_stock', 'permalink', 'average_rating', 'review_count'];

    public function images():HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function categories():BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags():BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function attributes():HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }
}

