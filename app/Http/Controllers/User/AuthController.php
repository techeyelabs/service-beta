<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Profile;
use Auth;
use Hash;
use Mail;
use Socialite;
use Validator;
use Cart;
use App\Mail\Common;
use App\Models\Authentic;
// use App\Http\Controllers\User\Profile;



class AuthController extends Controller
{
    public function __construct()
    {

    }

    public function user_registration_rules(array $data)
    {
      $messages = [
        'email.required' => ' この項目は必須です',
        'email.unique' => 'メールアドレスはすでに存在します'
      ];

      $validator = Validator::make($data, [
            'email' => 'required|email|unique:users'
      ], $messages);

      return $validator;
    }

    public function registerRequest(Request $request)
    {
        return view('authentication.register_request');
    }

    public function registerRequestAction(Request $request)
    {
        $validator = $this->user_registration_rules($request->toArray());
        if($validator->fails())
        {
          return redirect()->back()->withErrors($validator)->withInput();
        }

        $User = new User();
        $User->first_name = 'welcome user';
        $User->email = $request->email;
        $User->password = Hash::make(rand(100000,999999));
        $User->register_token = time().rand(1000,9999);
        $User->save();


        $emailData = [
            'name' => 'User',
            'contact_person' => 'Benri LTD',
            'register_token' => $User->register_token,
            'subject' => 'E mail verification for registration',
            'from_email' => 'noreply@Fund.net',
            'from_name' => 'Fund',
            'template' => 'authentication.email.registration_step1',
            'root'     => $request->root(),
            'email'     => $request->email
        ];

        Mail::to($request->email)
            ->send(new Common($emailData));
        return redirect()->back()->with('success_message', 'Verification mail sent. Please complete your registration by visiting the link sent to your mail.');

    }

    public function register(Request $request)
    {
        $token = $request->token;
        $data['user'] = User::where('register_token', $token)->first();
        if($data['user']){
            return view('authentication.register', $data);
        }
        else{
            return view('authentication.register_completed', $data);
        }
    }

    public function registerAction(Request $request)
    {
        // dd(2);
        $this->validate($request, [
            'first_name' => 'required|max:10',
            'last_name' => 'required|max:10',
            'email' => 'required',
            'password' => ['required',
            'min:8',
            'confirmed']
        ],[
            // 'password.regex'=> 'パスワードの書式が間違えています。確認してください。',
            'password.min' => 'Password must be at least 8 characters long',
            'password.confirmed' => 'Password and confirmation password did not match'
        ]);
        $count = 0;

        if(preg_match('/([a-z])/', $request->password)){
            $count++;
        }
        if(preg_match('/([A-Z])/', $request->password)){
            $count++;
        }
        if(preg_match('/([0-9])/', $request->password)){
            $count++;
        }
        //if(preg_match('/([!@#$%^&*])/', $request->password)){
        //     $count++;
        // }
        if($count < 2 || preg_match('/([!$%^&*]+)/', $request->password)){
            return redirect()->back()->withInput()->with('error_message', 'パスワードの書式が間違えています。確認してください。');
        }

        $User = User::where('register_token', $request->token)->first();
        $User->first_name = $request->first_name;
        $User->last_name = $request->last_name;
        $User->register_token = '';
        $User->password = Hash::make($request->password);
        $User->is_email_verified = true;
        $User->status = true;
        $User->save();

        $UserAuthentics = new Authentic();
        $UserAuthentics->user_id = $User->id;
        $UserAuthentics->save();

        $Profile = new Profile();
        $Profile->user_id = $User->id;
        $Profile->save();


        $emailData = [
            'name' => $User->first_name.' '.$User->last_name,
            'contact_person' => 'Benri LTD',
            'subject' => 'Registration completion',
            'from_email' => 'noreply@Fund.net',
            'from_name' => 'Fund',
            'template' => 'authentication.email.registration_step2',
            'root'     => $request->root()
        ];

        Mail::to($request->email)
            ->send(new Common($emailData));


        return redirect()->to(route('login'))->with('success_message', 'Registration Complete!
        You can now log in with the username and password you picked during your registration process.');

    }

    public function changePassword(Request $request)
    {
        return view('user.change_password');
    }

    public function changePasswordAction(Request $request)
    {
        // dd($request);
    	$this->validate($request, [
            'current_password' => 'required',
            // 'password' => 'required|confirmed'
            'password' => ['required',
            'min:8',
            'confirmed']
        ],[
            'password.min' => 'パスワードは８文字以上にする必要があります',
            'password.confirmed' => 'パスワードの確認が一致しません'
        ]);
        $count = 0;
        if(preg_match('/([a-z])/', $request->password)){
            $count++;
        }if(preg_match('/([A-Z])/', $request->password)){
            $count++;
        }
        if(preg_match('/([0-9])/', $request->password)){
            $count++;
        }
        //if(preg_match('/([!@#$%^&*])/', $request->password)){
        //     $count++;
        // }
        if($count < 2 || preg_match('/([!$%^&*]+)/', $request->password)){
            return redirect()->back()->withInput()->with('error_message', 'パスワードの書式が間違えています。確認してください。');
        }

        $User = User::find(Auth::user()->id);
        if (Hash::check($request->current_password, $User->password)) {
            $User->password = Hash::make($request->password);
            $User->save();
            return redirect()->back()->with('success_message', 'パスワードは正常に変更されました');
        }
        return redirect()->back()->with('error_message', '現在のパスワードが一致しません');


    }


    public function facebook(Request $request)
    {
		$redirectUrl = $request->root().'/facebook-action';
				// $redirectUrl = 'https://crofun.jp/facebook-action';
        return Socialite::driver('facebook')->setScopes(['email'])->redirectUrl($redirectUrl)->redirect();
    }

    public function facebookAction(Request $request)
    {
        $redirectUrl = $request->root().'/facebook-action';
        $user = Socialite::driver('facebook')->redirectUrl($redirectUrl)->user();
        $check = User::where('email', $user->email)->first();
        if($check){
            $check->facebook_id = $user->id;
            $check->is_email_verified = true;
            $check->status = true;
            $check->save();
            $userId = $check->id;
        }else{
            $User = new User();
            $User->facebook_id = $user->id;
            $User->first_name = $user->name;
            $User->pic = $user->avatar_original;
            $User->last_name = '';
            $User->email = $user->email;
            $User->is_email_verified = true;
            $User->status = true;
            $User->created_at = date('Y-m-d H:i:s');
            $User->updated_at = date('Y-m-d H:i:s');
            $User->save();

            $Profile = new Profile();
            $Profile->user_id = $User->id;
            $Profile->save();

            $userId = $User->id;


            // $this->updateProfile($User);

        }


        if(Auth::check()){
            return redirect()->to(route('user-social'))->with('success_message', 'Facebook connected!');
        }

        Auth::loginUsingId($userId, true);
        // return redirect()->intended(route('user-profile-update'));
        return redirect()->intended(route('user-my-page'));


    }


    public function google(Request $request)
    {
        $redirectUrl = $request->root().'/google-action';
        return Socialite::driver('google')->setScopes(['email'])->redirectUrl($redirectUrl)->redirect();
    }

    public function googleAction(Request $request)
    {
        $redirectUrl = $request->root().'/google-action';
        $user = Socialite::driver('google')->redirectUrl($redirectUrl)->user();
        // dd($user);
        $check = User::where('email', $user->email)->first();
        if($check){
            $check->google_id = $user->id;
            $check->is_email_verified = true;
            $check->status = true;
            $check->save();
            $userId = $check->id;
        }else{
            $User = new User();
            $User->google_id = $user->id;
            $User->first_name = $user->name;
            $User->pic = $user->avatar_original;
            $User->last_name = '';
            $User->email = $user->email;
            $User->is_email_verified = true;
            $User->status = true;
            $User->created_at = date('Y-m-d H:i:s');
            $User->updated_at = date('Y-m-d H:i:s');
            $User->save();

            $Profile = new Profile();
            $Profile->user_id = $User->id;
            $Profile->save();

            $userId = $User->id;

            // $this->updateProfile($User);

        }


        if(Auth::check()){
            return redirect()->to(route('user-social'))->with('success_message', 'Google connected!');
        }
        Auth::loginUsingId($userId, true);
        // return redirect()->intended(route('user-profile-update'));
        return redirect()->intended(route('user-my-page'));


    }


    public function twitter(Request $request)
    {
        $redirectUrl = $request->root().'/twitter-action';
        return Socialite::driver('twitter')->redirect();
    }

    public function twitterAction(Request $request)
    {
        $redirectUrl = $request->root().'/twitter-action';
        $user = Socialite::driver('twitter')->user();
        // dd($user);
        $check = User::where('twitter_id', $user->id)->first();
        if($check){
            $check->twitter_id = $user->id;
            $check->is_email_verified = true;
            $check->status = true;
            $check->save();
            $userId = $check->id;
        }else{
            $User = new User();
            $User->twitter_id = $user->id;
            $User->first_name = $user->name;
            $User->pic = $user->avatar_original;
            $User->last_name = '';
            $User->email = 'user'.$User->twitter_id.rand(1000,9999).'@example.com';
            $User->is_email_verified = true;
            $User->status = true;
            $User->created_at = date('Y-m-d H:i:s');
            $User->updated_at = date('Y-m-d H:i:s');
            $User->save();

            $Profile = new Profile();
            $Profile->user_id = $User->id;
            $Profile->save();

            $userId = $User->id;

            // $this->updateProfile($User);

        }


        if(Auth::check()){
            return redirect()->to(route('user-social'))->with('success_message', 'Twitter connected!');
        }

        Auth::loginUsingId($userId, true);
        // return redirect()->intended(route('user-profile-update'));
        return redirect()->intended(route('user-my-page'));

    }


    public function yahoo(Request $request)
    {
        $redirectUrl = $request->root().'/yahoo-action';
        return Socialite::driver('yahoo')->redirect();
    }

    public function yahooAction(Request $request)
    {
        $redirectUrl = $request->root().'/yahoo-action';
        $user = Socialite::driver('yahoo')->user();
        // dd($user);
        $check = User::where('email', $user->email)->first();
        if($check){
            $check->twitter_id = $user->id;
            $check->is_email_verified = true;
            $check->status = true;
            $check->save();
            $userId = $check->id;
        }else{
            $User = new User();
            $User->google_id = $user->id;
            $User->first_name = $user->name;
            $User->pic = $user->avatar_original;
            $User->last_name = '';
            $User->email = $user->email;
            $User->is_email_verified = true;
            $User->status = true;
            $User->created_at = date('Y-m-d H:i:s');
            $User->updated_at = date('Y-m-d H:i:s');
            $User->save();

            $Profile = new Profile();
            $Profile->user_id = $User->id;
            $Profile->save();

            $userId = $User->id;

            // $this->updateProfile($User);

        }

        if(Auth::check()){
            return redirect()->to(route('user-social'))->with('success_message', 'Yahoo connected!');
        }

        Auth::loginUsingId($userId, true);
        // return redirect()->intended(route('user-profile-update'));
        return redirect()->intended(route('user-my-page'));

    }



    public function logout()
    {
        Cart::destroy();
    	Auth::logout();
    	return redirect()->to(route('front-home'));
    }
}
