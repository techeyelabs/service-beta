<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'id',
        'profession ',
        'utility',
        'age_group',
        'sex',
        'residential_area',
        'personal_details',
        'profile_image',
        'created_at',
        'updated_at'
    ];

    protected $table = 'profile';
    protected $primaryKey = 'id';

    public function ageGroup()
    {
        return $this->belongsTo('App\Models\AgeGroup', 'age_group');
    }

    public function residentialArea()
    {
        return $this->belongsTo('App\Models\Residential', 'residential_area');
    }



    public function sexName()
    {
        return $this->belongsTo('App\Models\Sex', 'sex');
    }
}
