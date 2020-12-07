<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'id',
        'seller_id',
        'service_image',
        'category',
        'title',
        'details',
        'status',
        'production_date',
        'created_at',
        'updated_at'
    ];

    protected $table = 'portfolio';
    protected $primaryKey = 'id';
}
