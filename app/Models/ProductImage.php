<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'id',
        'product_id',
        'image',
        'image_type',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $table = 'product_images';
    protected $primaryKey = 'id';
}
