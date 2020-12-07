<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category_2 extends Model
{
     protected $fillable = [
        		'id',
			    'parent_id',
			    'cat_name',
			    'status',
			    'created_at',
			    'updated_at'
    ];
    
    protected $table = 'category';
    protected $primaryKey = 'id';
}
