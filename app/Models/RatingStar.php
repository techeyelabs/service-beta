<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingStar extends Model
{
    protected $fillable = [
        'id',
        'buyer_id ',
        'product_id',
        'rating_star',
        'created_at',
        'updated_at'
    ];

    protected $table = 'product_rating';
    protected $primaryKey = 'id';
}
