<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImmediateHolding extends Model
{
    protected $fillable = [
        'id',
        'product_id',
        'request_id',
        'response_id',
        'price',
        'seller_id',
        'buyer_id',
        'aff_commission',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $table = 'intermediate_holding';
    protected $primaryKey = 'id';
}
