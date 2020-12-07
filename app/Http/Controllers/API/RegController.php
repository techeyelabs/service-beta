<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiErrorCodes;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserPointWithdrawal;
use App\Mail;
use DB;

class RegController extends Controller
{
    public function store(Request $request)
    {
        echo json_decode($request);
        exit;
        // $test_data = DB::table('units')->get();
        // return response($test_data)->header('Content-type','application/json');
    }
    public function seller_log(Request $request)
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
            $is_exist = User::where('email', $email)->where('password', $pass)->where('user_type_id', 2)->where('status', 'ACTIVE')->first(); 
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
                        'img_sidebar'=>  $ip  ."assets/images/users/" .  $is_exist->profile_pic,  
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

    public function seller_reg_first(Request $request)
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

    public function seller_reg_second(Request $request)
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
                    $use = new User();
                    $use->email = $request->email;
                    $use->first_name = $request->name;
                    $use->password = sha1($request->password);
                    $use->user_type_id = 2;
                    $use->status = 'INACTIVE';
                    $use->activation_code = rand(1000, 9999).time();
                    // $u->sex = $request->sex;
                    $use->profile_pic = $filename;
                    $use->save();
                    //Confirmation mail
                    $data = [
                        'link' => route('activate-seller-account', ['code' => $use->activation_code]),
                        'name' => $request->name
                    ];
                    \Mail::send('MailConfirm', $data, function($message) use ($use){
                        $message->to($use->email, 'test')->subject
                           ('Confirm your registered email address');
                        $message->from('service@benri.com.bd','Service.CrowdVillage');
                     });
                    //Confirmation mail ends

                    $last_insert_id =  $use->id;
                    //Profile table update
                    $is_exist = Profile::where('user_id', $last_insert_id)->count();
                    if($is_exist==0){
                        $pro = new Profile();
                        $pro->user_id = $last_insert_id;
                        $pro->profile_image = $filename;
                        $pro->save();

                        $pw = new UserPointWithdrawal();
                        $pw->user_id = $last_insert_id;
                        $pw->remaining_amount = 0;
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

    public function check_email(Request $request)
    {
        $mail = $request->email;
        $name = $request->name;
        $user_type = $request->user_type;
        if(empty($mail)){
            $e = ApiErrorCodes::where('error_code',10072)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ];
        } else if(empty($name)){
            $e = ApiErrorCodes::where('error_code',10072)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ];
        } else if(empty($user_type)){
            $e = ApiErrorCodes::where('error_code',10072)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ];
        } else {
            $is_exist = User::where('email', $mail)->where('user_type_id', $user_type)->count();
            if($is_exist>0){
                $is_exist = User::where('email', $mail)->where('user_type_id', $user_type)->first(); 
                if(!empty($is_exist)){
                    $login_time =  date('Y-m-d H:i:s');
                    $is_exist->last_login_time = $login_time;
                    $is_exist->update();
                    $ip = \Config::get('app.api_base_url');
                    $data = [
                        'status'  => 200 ,
                        'payload' => [
                            'id' => $is_exist->id,
                            'email'=> $mail,
                            'first_name'=> $is_exist->first_name, 
                            'last_name'=> $is_exist->last_name,    
                            'phone'=> $is_exist->phone,  
                            'address'=> $is_exist->address,  
                            'profile_pic'=> $is_exist->profile_pic,  
                            'img_sidebar'=>  $ip  ."assets/images/users/" .  $is_exist->profile_pic,  
                            'user_type_id'=> $is_exist->user_type_id,  
                            'last_login_time'=> $is_exist->last_login_time,  
                            'last_login_ip '=> $is_exist->last_login_ip   
                        ] 
                    ];
                }
            } else {
                $is_exist = User::where('email', $mail)->first();
                if(!empty($is_exist)){
                    // $login_time =  date('Y-m-d H:i:s');
                    // $is_exist->last_login_time = $login_time;
                    // $is_exist->update();
                    // $ip = \Config::get('app.api_base_url');
                    // $data = [
                    //     'status'  => 200 ,
                    //     'payload' => [
                    //         'id' => $is_exist->id,
                    //         'email'=> $mail,
                    //         'first_name'=> $is_exist->first_name, 
                    //         'last_name'=> $is_exist->last_name,    
                    //         'phone'=> $is_exist->phone,  
                    //         'address'=> $is_exist->address,  
                    //         'profile_pic'=> $is_exist->profile_pic,  
                    //         'img_sidebar'=>  $ip  ."assets/images/users/" .  $is_exist->profile_pic,  
                    //         'user_type_id'=> $is_exist->user_type_id,  
                    //         'last_login_time'=> $is_exist->last_login_time,  
                    //         'last_login_ip '=> $is_exist->last_login_ip  
                    //     ] 
                    // ];
                    $e = ApiErrorCodes::where('error_code',10074)->first(); 
                    $data = [
                        'status'  => $e->error_code,
                        'message' => $e->error_message
                    ];
                } else {
                    $us = new User();
                    $us->email = $mail;
                    $us->first_name = $name;
                    $us->password = 'logged_in_by_sns';
                    $us->user_type_id = $user_type;
                    $us->status = 'ACTIVE';
                    $us->profile_pic = 'blank.png';
                    $us->save();
                    $last_insert_id =  $us->id;

                    $is_exist = Profile::where('user_id', $last_insert_id)->count();
                    if($is_exist==0){
                        $pro = new Profile();
                        $pro->user_id = $last_insert_id;
                        $pro->profile_image = 'blank.png';
                        $pro->save();

                        $pw = new UserPointWithdrawal();
                        $pw->user_id = $last_insert_id;
                        $pw->remaining_amount = 0;
                        $pw->save();
                    }

                    $is_exist = User::where('email', $mail)->where('password', '=', 'logged_in_by_sns')->where('user_type_id', $user_type)->first(); 
                    if(!empty($is_exist)){
                        $login_time =  date('Y-m-d H:i:s');
                        $is_exist->last_login_time = $login_time;
                        $is_exist->update();
                        $ip = \Config::get('app.api_base_url');
                        $data = [
                            'status'  => 200 ,
                            'payload' => [
                                'id' => $is_exist->id,
                                'email'=> $mail,
                                'first_name'=> $is_exist->first_name, 
                                'last_name'=> $is_exist->last_name,    
                                'phone'=> $is_exist->phone,  
                                'address'=> $is_exist->address,  
                                'profile_pic'=> $is_exist->profile_pic,  
                                'img_sidebar'=>  $ip  ."assets/images/users/" .  $is_exist->profile_pic,  
                                'user_type_id'=> $is_exist->user_type_id,  
                                'last_login_time'=> $is_exist->last_login_time,  
                                'last_login_ip '=> $is_exist->last_login_ip  
                            ] 
                        ];
                    }
                }               
            }
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
        
    }

    public function activateSellerAccount(Request $request)
    {
        if(empty($request->code)) abort(404);
        $User = User::where('activation_code', $request->code)->first();
        if($User){
            $User->activation_code = null;
            $User->status = 'ACTIVE';
            $User->save();
            //Confirmation mail
            $data = [
                'link' => '',
                'name' => $User->first_name,
                'email' => $User->email
            ];
            \Mail::send('RegComplete', $data, function($message) use ($User){
                $message->to($User->email, 'test')->subject
                   ('Confirm your registered email address');
                $message->from('service@benri.com.bd','Service.CrowdVillage');
             });
            //Confirmation mail ends
            return redirect()->to($request->root().'/login/seller');
        }
        abort(404);
    }
    public function activateBuyerAccount(Request $request)
    {
        if(empty($request->code)) abort(404);
        $User = User::where('activation_code', $request->code)->first();
        if($User){
            $User->activation_code = null;
            $User->status = 'ACTIVE';
            $User->save();
             //Confirmation mail
            $data = [
                'link' => '',
                'name' => $User->first_name,
                'email' => $User->email
            ];
            \Mail::send('RegComplete', $data, function($message) use ($User){
                $message->to($User->email, 'test')->subject
                   ('[share-work] 本登録が完了しました');
                $message->from('noreply@share-work.jp','ShareWork');
             });
            //Confirmation mail ends
            return redirect()->to($request->root().'/login');
        }
        abort(404);
    }
}
