<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'car_number',
        'total_price',
        'status',
        'image',
    ];
}
