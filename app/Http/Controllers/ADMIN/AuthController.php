<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use Auth;

class AuthController extends Controller
{
	/*public function __construct()
    {
        
    }*/

    public function login()
    {

    	return view('admin.login');
    }

    
    public function loginAction(Request $request)
    {
        //dd($request);

        
    	$this->validate($request, [	        
	        'email' => 'required|email|max:255',	        
	        'password' => 'required'
	    ]);
    	if (Auth::guard('admin')->attempt(['email' => $request->get('email'), 'password' => $request->get('password'), 'status' => 1], $request->get('remember'))) {
            // Authentication passed...
            $user_id = Auth::guard('admin')->user()->id;
            //$AdminUser = AdminUser::find($user_id);
            //$AdminUser->save();
            return redirect()->intended('admin/dashboard');
        }
        else{
            return redirect()->back()->with('error_message', 'Email or Password mismatch, Please try again.');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->to('admin/login');
    }

    
}