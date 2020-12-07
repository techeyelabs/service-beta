<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = [
        'id',
        'seller_id',
        'buyer_id',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $table = 'follow';
    protected $primaryKey = 'id';
}
