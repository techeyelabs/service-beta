<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bank;
use App\Models\District;
use App\Models\Authentic;
use App\Models\Product;
use App\Models\Profile;
use Auth;
use Image;

class ProfileController extends Controller
{
    public function visitProfile(Request $request){

        $user = User::find($request->id);
        $service = Product::where('user_id', $request->id)->get();
        if (Auth::check())
        {
            if (Auth::user()->id == $request->id){
                return redirect()->route('my-profile');
            } else if(Auth::user()->id != $request->id) {
                return View('profile.visitor_page', ['user' => $user, 'service' => $service]);
            }
        }
        else
        {
            return View::make('profile.visitor_page', ['user' => $user, 'service' => $service]);
        }

    }
    public function myProfile(Request $request){

        $user_id = Auth::user()->id;
        $user = User::where('id', Auth::user()->id)->with('profile')->first();
        $data['user'] = $user;
        $data['shipping'] = User::shippings($user_id);
        $data['banks'] = Bank::all();
        $data['districts'] = District::all();
        $data['authentics'] = Authentic::where('user_id', $user_id)->first();
        $projects = Product::where('user_id', $user_id )->get();
        $data['projects'] = $projects;
        $data['products'] = Product::where('user_id', $user_id )->get();

        return view('profile.user-my-page', $data);
    }
      //Banner Pic and Profile Pic Image data Update
      public function updatePic(Request $request)
      {
          $user = Auth::user()->id;
          $user_sec = User::find($user);
          $profile_sec = Profile::where('user_id', $user)->first();

             //insert dp and banner into users table
             if ($request->hasFile('dis_p')) {
              $extension = $request->dis_p->extension();
              $name = time().rand(1000,9999).'.'.$extension;
              // $path = $request->image->storeAs('products', $name);
              $img = Image::make($request->dis_p)->resize(300, 300);
              $img->save(public_path().'/uploads/profile/'.$name, 60);
              $user_sec->pic = 'profile/'.$name;
          }
          if ($request->hasFile('ban_p')) {
              $extension = $request->ban_p->extension();
              $name = time().rand(1000,9999).'.'.$extension;
              // $path = $request->image->storeAs('products', $name);
              $img = Image::make($request->ban_p);
              $img->save(public_path().'/uploads/banner/'.$name, 60);
              $user_sec->banner = 'banner/'.$name;
          }
          $user_sec->save();
          return redirect()->back();

      }
      public function updatebasic(Request $request)
      {

          $user = Auth::user()->id;

          //get user table instance
          $user_sec = User::find($user);
          //get profile table instance
          $profile_sec = Profile::where('user_id', $user)->first();

          //user table data update
          $user_sec->first_name = $request->fname;
          $user_sec->last_name = $request->lname;
          $user_sec->email = $request->email;
          $user_sec->save();

          //profile table data update
          $profile_sec->phone_no = $request->phone;
          $profile_sec->address = $request->address;
          $profile_sec->save();

          return redirect()->back();

      }
      public function updateintro(Request $request)
      {
          $user = Auth::user()->id;
          $profile_sec = Profile::where('user_id', $user)->first();
          $profile_sec->self_intro = $request->intro;
          $profile_sec->save();
          return redirect()->back();

      }
      public function updatepayment(Request $request)
      {
          //Get user id
          $user = Auth::user()->id;
          $nidupdate = Authentic::where('user_id', $user)->first();
             //insert nid back images
             if ($request->hasFile('nid_front')) {
              $extension = $request->nid_front->extension();
              $name = time().rand(1000,9999).'.'.$extension;
              // $path = $request->image->storeAs('products', $name);
              $img = Image::make($request->nid_front)->resize(300, 300);
              $img->save(public_path().'/uploads/authentics/'.$name, 60);
              $nidfront = 'authentics/'.$name;
              $nidupdate->nid_front = $nidfront;
          }
           //insert nid back images
          if ($request->hasFile('nid_back')) {
              $extension = $request->nid_back->extension();
              $name = time().rand(1000,9999).'.'.$extension;
              // $path = $request->image->storeAs('products', $name);
              $img = Image::make($request->nid_back)->resize(300, 300);
              $img->save(public_path().'/uploads/authentics/'.$name, 60);
              $nidback = 'authentics/'.$name;
              $nidupdate->nid_back = $nidback;
          }
          $newAuths = Authentic::updateOrCreate([
              'user_id'   => $user,
          ],[
              'bank'     => $request->bank,
              'branch_name' => $request->branch,
              'district'    => $request->district,
              'account_name'   => $request->acc_name,
              'account_no'       => $request->acc_no,
              'bkash'   => $request->bkash_no,
          ]);
          $nidupdate->save();
          return redirect()->back();
      }


}
