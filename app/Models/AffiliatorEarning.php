<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatorEarning extends Model
{
    protected $fillable = [
        'id',
        'child_affiliator_id',
        'parent_affiliator_id',
        'product_id',
        'quantity',
        'price',
        'earning_amount',
        'created_at',
        'updated_at'
    ];

    protected $table = 'affiliator_earnings';
    protected $primaryKey = 'id';

    public function childAffiliator()
    {
        return $this->belongsTo('App\Models\User', 'child_affiliator_id');
    }


    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
