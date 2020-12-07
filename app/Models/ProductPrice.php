<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $fillable = [
        'id',
        'attr_id',
        'price',
        'discount_price',
        'created_at',
        'updated_at'
    ];

    protected $table = 'product_prices';
    protected $primaryKey = 'id';
}
