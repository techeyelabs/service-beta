<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopSlider extends Model
{
    protected $fillable = [
        'id',
        'seller_id',
        'buyer_id',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $table = 'top_slider';
    protected $primaryKey = 'id';
}
