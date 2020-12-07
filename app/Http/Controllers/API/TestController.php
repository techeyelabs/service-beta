<?php

namespace App\Http\Controllers\API;
use App\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminUser;
use App\Models\Product;
use App\Models\Favourite;

class TestController extends Controller
{
    public function index()
    {
        // $result = Product::where('id', 138)->withCount('rating')->first();
        
        $result = Favourite::where('user_id', 37)->with('user')->with('product')->get();
        dd($result[0]->user->first_name, $result[0]->product->avgRating());
    }
    public function form()
    {
    	return view('backend.sample_form');
    }

    public function user_profile()
    {
    	return view('backend.user_profile');
    }

    public function sendEmail()
    {
        // $data['title'] = "This is Test Mail Tuts Make";
        // dd(env('MAIL_USERNAME'), env('MAIL_PASSWORD'));
        $data = [
            'link' => 'google.com',
            'name' => 'Tanvir'
        ];
            \Mail::send('registration', $data, function($message) {
                $message->to('tanvir0604@gmail.com', 'test')->subject
                   ('this is trial version of a feature');
                $message->from('safkatsabik@gmail.com','sabik');
             });
        
         return response()->json($data);
    }

    public function test()
    {
        //dd(date('Y-m-d 00:00:01', strtotime('first day of last month')), date('Y-m-d 12:59:59', strtotime('last day of previous month')));
        $aff = new \App\Libraries\Affiliator();
        $aff->recruiting_bonus();

    }


}
