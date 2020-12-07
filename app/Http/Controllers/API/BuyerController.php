<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiErrorCodes;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserPointWithdrawal;
use DB;

class BuyerController extends Controller
{
    public function login()
    {
        $test_data = DB::table('units')->get();
        return response($test_data)->header('Content-type','application/json');
    }

    public function store(Request $request)
    {
        echo json_decode($request);
        exit;
        // $test_data = DB::table('units')->get();
        // return response($test_data)->header('Content-type','application/json');
    }
    public function buyer_log(Request $request)
    {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        $email = $request->email ; 
        $password = $request->password ; 
        // echo $email; echo $password; exit;  
        if(empty($email))
        {
           $e = ApiErrorCodes::where('error_code',10021)->first(); 
           $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];

        }
        else if(empty($password))
        {
           $e = ApiErrorCodes::where('error_code',10020)->first(); 
           $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];
        }
        else if( !preg_match($regex, $email) ) {
	        $e = ApiErrorCodes::where('error_code',10022)->first(); 
        	$data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        }
        else{  
            $pass = sha1($password);
            $is_exist = User::where('email', $email)->where('password', $pass)->where('user_type_id', 1)->first(); 
            if(!empty($is_exist)){
                $login_time =  date('Y-m-d H:i:s');
                $is_exist->last_login_time = $login_time;
                $is_exist->update();
                $ip = \Config::get('app.api_base_url');
                $data = [
                    'status'  => 200 ,
                    'payload' => [
                        'id' => $is_exist->id,
                        'email'=> $email,
                        'first_name'=> $is_exist->first_name, 
                        'last_name'=> $is_exist->last_name,    
                        'phone'=> $is_exist->phone,  
                        'address'=> $is_exist->address,  
                        'profile_pic'=> $is_exist->profile_pic, 
                        'img_sidebar' => $ip  ."assets/images/users/" .  $is_exist->profile_pic, 
                        'user_type_id'=> $is_exist->user_type_id,  
                        'last_login_time'=> $is_exist->last_login_time,  
                        'last_login_ip '=> $is_exist->last_login_ip  
                    ] 
                ];
            }
            else{
                $e = ApiErrorCodes::where('error_code',10023)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            }
           
        }        

        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
        
    }

    public function buyer_reg_first(Request $request)
    {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        $email = isset($request->email)?$request->email : '';
        if(empty($email)) {
            $e = ApiErrorCodes::where('error_code',10021)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ];
        }else{
            if(preg_match($regex, $email)){
                
                $is_exist = User::where('email', $email)->first(); 
                if($is_exist){
                    //===========if email exist
                    $e = ApiErrorCodes::where('error_code',10024)->first();
                    $data = [
                        'status'  => $e->error_code  ,
                        'message' => $e->error_message
                    ];
                }else{
                    //========= success
                    $data = [
                        'status'  => 200,
                        'email' => $email
                    ];
                }
            }else{
                //========= invalid email
                $e = ApiErrorCodes::where('error_code',10022)->first(); 
                $data = [
                    'status'  => $e->error_code,
                    'message' => $e->error_message
                ];
            }
        }
        
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function buyer_reg_second(Request $request)
    {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        $flag = 0;
        if(!empty($request->file)){
            $extension = $request->file('file')->getClientOriginalExtension();
            $dir = 'assets/uploads/';
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $request->file('file')->move($dir, $filename);
        } else {
            $filename = 'blank.png';
        }

        if($request->email=='' || $request->email==null){
            $e = ApiErrorCodes::where('error_code',10021)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ];
        }      
        else if($request->name=='' || $request->name==null){
            $e = ApiErrorCodes::where('error_code',10027)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ];
        }
        else if($request->password=='' || $request->password==null){
            $e = ApiErrorCodes::where('error_code',10029)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ];
        }
        else if(!preg_match($regex, $request->email)) {
            $e = ApiErrorCodes::where('error_code',10022)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ];
        } else {
                $is_exist = User::where('email', $request->email)->first(); 
                if(!empty($is_exist)){
                    $e = ApiErrorCodes::where('error_code',10024)->first();
                    $data = [
                        'status'  => $e->error_code  ,
                        'message' => $e->error_message
                    ];
                }
                else{
                    $u = new User();
                    $u->email = $request->email;
                    $u->first_name = $request->name;
                    $u->password = sha1($request->password);
                    $u->user_type_id = 1;
                    $u->status = 'INACTIVE';
                    $u->activation_code = rand(1000, 9999).time();
                    // $u->sex = $request->sex;
                    $u->profile_pic = $filename;
                    $u->save();

                     //Confirmation mail
                     $data = [
                        'link' => route('activate-buyer-account', ['code' => $u->activation_code]),
                        'name' => $request->name,
                        'email' => $request->email
                    ];
                    \Mail::send('MailConfirm', $data, function($message) use ($u){
                        $message->to($u->email, 'test')->subject
                           ('[share-work] ご登録メールアドレスの確認');
                        $message->from('noreply@share-work.jp','ShareWork');
                     });
                    //Confirmation mail ends

                    $last_insert_id =  $u->id;
                    //Profile table update
                    $is_exist = Profile::where('user_id', $last_insert_id)->count();
                    if($is_exist==0){
                        $pro = new Profile();
                        $pro->user_id = $last_insert_id;
                        $pro->profile_image = $filename;
                        $pro->save();

                        $pw = new UserPointWithdrawal();
                        $pw->user_id = $last_insert_id;
                        $pw->remaining_amount = 1450000;
                        $pw->save();
                    }
                    $e = ApiErrorCodes::where('error_code',10026)->first(); 
                    $data = [
                        'status'  => 200,
                        'message' => $e->error_message
                    ];
                    
                }  
          
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function activateBuyerAccount(Request $request)
    {
        if(empty($request->code)) abort(404);
        $User = User::where('activation_code', $request->code)->first();
        if($User){
            $User->activation_code = null;
            $User->status = 'ACTIVE';
            $User->save();
            return redirect()->to($request->root().'/login');
        }
        abort(404);
    }
}
