<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'id',
        'product_id',
        'buyer_id',
        'seller_id',
        'comment',
        'created_at',
        'updated_at'
    ];

    protected $table = 'product_review';
    protected $primaryKey = 'id';
}
