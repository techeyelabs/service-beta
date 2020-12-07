<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestResponse extends Model
{
    protected $fillable = [
        'id',
        'request_id',
        'seller_id',
        'content',
        'estimated_price',
        'estimated_deadline',
        'created_at',
        'updated_at'
    ];

    protected $table = 'request_response';
    protected $primaryKey = 'id';
}
