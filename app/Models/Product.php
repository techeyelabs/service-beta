<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'id',
        'seller_id',
        'category_id',
        'title',
        'short_desc',
        'long_desc',
        'commision_in_ex_vat',
        'commision_include_vat_amount',
        'affiliator_commission',
        'product_link',
        'created_at',
        'updated_at',
    ];

    protected $table = 'products';
    protected $primaryKey = 'id';

    public function price()
    {
        return $this->belongsTo('App\Models\ProductPrice', 'price_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo('App\Models\Category', 'sub_category_id');
    }

    public function seller()
    {
        return $this->belongsTo('App\Models\User', 'seller_id');
    }

    public function rating()
    {
        return $this->hasMany('App\Models\RatingStar', 'product_id');
    }

    public function multipleImages()
    {
        return $this->hasMany('App\Models\Multiples', 'product_id');
    }

    public function avgRating()
    {
        return \App\Models\RatingStar::where('product_id', $this->id)->avg('rating_star');
    }

}
