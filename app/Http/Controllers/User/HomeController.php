<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use DB;
use Carbon\Carbon;
use App\Mail\Common;



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

        // $products = $this->productsData($request);
        $big_one = $this->big_one();
        $small_four = $this->small_four();
        // $data['products'] = $products;
        $data['big_one'] = $big_one;
        $data['small_four'] = $small_four;
        // $data['rev'] = Project::where('status', 1)->orderBy('id', 'DESC')->limit(4)->get();
        // $data['latest1'] = Product::where('status', 1)->where('start', '<', Carbon::now())->where('end', '>', Carbon::now())->orderBy('start', 'DESC')->get();
        // $data['latest2'] = Product::where('status', 1)->where('end', '<', Carbon::now())->orderBy('end', 'DESC')->get();
        // $data['latest3'] = Product::where('status', 1)->where('start', '>', Carbon::now())->orderBy('start', 'DESC')->get();
        // $data['latest'] = $data['latest1']->merge($data['latest2'])->merge($data['latest3'])->take(4);
        // $data['donation'] = Project::where('status', 1)->orderBy('total_collection', 'DESC')->limit(4)->get();
        // $data['endSoon'] = Project::where('status', 1)->where('end', '>', Carbon::now())->where('start', '<', Carbon::now())->orderBy('end', 'ASC')->limit(4)->get();
        // $data['startSoon'] = Project::where('status', 1)->where('start', '>', Carbon::now())->orderBy('start', 'ASC')->limit(4)->get();
         $data['latest'] = Product::where('status', 0)->orderBy('id', 'DESC')->limit(4)->get();
         $data['donation'] = Product::where('status', 0)->orderBy('id', 'DESC')->limit(4)->get();
         $data['endSoon'] = Product::where('status', 0)->orderBy('id', 'DESC')->limit(4)->get();
         $data['startSoon'] = Product::where('status', 0)->orderBy('id', 'DESC')->limit(4)->get();

            return view('systems.homepage', $data);
    }

    public function big_one()
    {
        $data = Product::where('status', 0)->orderBy('id', 'DESC')->first();
        return $data;
    }

    public function small_four()
    {
        $data = Product::where('status', 0)->orderBy('id', 'DESC')->offset(1)->limit(4)->get();
        // dd($data);
        return $data;
    }


        public function logout()
        {
            Auth::logout();
            return redirect()->to(route('homepage'));
        }

}
