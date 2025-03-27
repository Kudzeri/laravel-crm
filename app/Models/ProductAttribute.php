<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductAttribute extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'value',
    ];

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
