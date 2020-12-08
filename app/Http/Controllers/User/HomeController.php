<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use DB;
use Carbon\Carbon;
use App\Mail\Common;


use Cart;
use Auth;
use Mail;
Use Alert;
use View;

class HomeController extends Controller
{
    public function loginmethod(Request $request){
        // dd($request);
    //     if (!Auth::attempt($request->only(['email', 'password']) )) {
    //   return redirect()->back()->with('error', 'Credentials do not match');
    //     }
    //     return redirect()->route('home', $request->id);
    return view('authentication.login');

    }
    public function loginaction(Request $request){
        if (!Auth::attempt($request->only(['email', 'password']) )) {
            return redirect()->back()->with('error', 'Credentials do not match');
        }
              return redirect()->route('homepage', ['id' => $request->id]);
    }
    public function homepage(Request $request){

            //   return redirect()->route('homepage', ['id' => $request->id]);
            return view('systems.homepage');


    }
}
