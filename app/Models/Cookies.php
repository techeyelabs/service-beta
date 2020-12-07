<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cookies extends Model
{
    protected $fillable = [
        'id',
        'buyer_id',
        'product_id',
        'affiliator_id',
        'created_at'
    ];

    protected $table = 'cookies_alternate';
    protected $primaryKey = 'id';
}
