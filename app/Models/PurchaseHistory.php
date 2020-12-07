<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseHistory extends Model
{
    protected $fillable = [
        'id',
        'product_id ',
        'seller_id',
        'buyer_id',
        'service_id',
        'price',
        'payment_method_id',
        'transaction_id',
        'created_at',
        'updated_at'
    ];

    protected $table = 'purchase_history';
    protected $primaryKey = 'id';

    public function buyer()
    {
        return $this->belongsTo('App\Models\User', 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo('App\Models\User', 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
