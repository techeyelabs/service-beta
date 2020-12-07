<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminUser;

class TestController extends Controller
{
    public function form()
    {
    	return view('backend.sample_form');
    }

    public function user_profile()
    {
    	return view('backend.user_profile');
    }

}
