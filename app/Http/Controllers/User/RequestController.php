<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\RequestBoard;
use Auth;

class RequestController extends Controller
{
   public function loadRequestForm()
   {
    $data['categories'] = Category::get();
       return view("request.request", $data);

   }
   public function storeRequest(Request $request)
   {
    $Request = new RequestBoard();
    $Request->buyer_id = Auth::user()->id;
    $Request->title = $request->title;
    $Request->category = $request->category;
    $Request->content = $request->details;
    $Request->save();
    return redirect()->route('my-request');
   }

   public function myrequest(Request $request)
   {
    $data['requests'] = RequestBoard::get();
    return view("request.myrequest", $data);
   }
   public function myrequestdetails(Request $request)
   {
    $data['requestdetails'] = RequestBoard::where('id', $request->id)->first();

    return view('request.myrequestdetails', $data);
   }
}
