<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'id',
        'sender_id',
        'receiver_id',
        'seller_id',
        'buyer_id',
        'content',
        'product_id',
        'type',
        'opening_status',
        'created_at',
        'updated_at'
        
    ];

    protected $table = 'chat';
    protected $primaryKey = 'id';

    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'sender_id');
    }
    public function receiver()
    {
        return $this->belongsTo('App\Models\User', 'receiver_id');
    }
}
