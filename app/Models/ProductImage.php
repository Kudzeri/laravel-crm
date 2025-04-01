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

    public function getImageUrlArrayAttribute(): array
    {
        return json_decode($this->image_url, true) ?? [];
    }

    public function getThumbnailAttribute($value): ?string
    {
        return json_decode($this->thumbnail, true) ?? [];
    }

}

