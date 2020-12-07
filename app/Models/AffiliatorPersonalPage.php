<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatorPersonalPage extends Model
{
    protected $fillable = [
        'id',
        'affiliator_id',
        'product_id',
        'created_at',
        'updated_at'
    ];

    protected $table = 'affiliator_personal_page';
    protected $primaryKey = 'id';

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function affiliator()
    {
        return $this->belongsTo('App\Models\User', 'affiliator_id');
    }
}
