<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use DB;

class StaticController extends Controller
{
    public function get_static($id)
    {
        $text = DB::table('static_info')->where('id', $id)->first();
        $data = [
            'status' => 200,
            'text' => $text->details
        ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
}
