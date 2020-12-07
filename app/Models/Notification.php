<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'product_id',
        'request_id',
        'response_id',
        'seller_id',
        'buyer_id',
        'notification_text',
        'notification_type',
        'created_at',
        'updated_at'
    ];

    protected $table = 'notification';
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
