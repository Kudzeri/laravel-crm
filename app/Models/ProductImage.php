<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_url',
        'thumbnail',
        'alt',
    ];

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::creating(function ($image) {
            if (!$image->thumbnail) {
                $image->thumbnail = $image->src;
            }
        });
    }

    public function getImageUrlAttribute($value): string
    {
        return config('app.url') . '/' . ltrim($value, '/');
    }

    public function getThumbnailAttribute($value): ?string
    {
        return $value ? config('app.url') . '/' . ltrim($value, '/') : null;
    }

}

