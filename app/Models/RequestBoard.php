<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestBoard extends Model
{
    protected $fillable = [
        'id',
        'buyer_id',
        'category',
        'sub_category',
        'title',
        'content',
        'application_date',
        'budget',
        'created_at',
        'updated_at'
    ];

    protected $table = 'request_board';
    protected $primaryKey = 'id';

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function buyer()
    {
        return $this->belongsTo('App\Models\User', 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo('App\Models\User', 'accepted_seller_id');
    }

}
