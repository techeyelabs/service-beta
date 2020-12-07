<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multiples extends Model
{
    protected $fillable = [
        'id',
        'product_id',
        'image',
        'created_at',
        'updated_at'
    ];

    protected $table = 'multiple_image';
    protected $primaryKey = 'id';
}
