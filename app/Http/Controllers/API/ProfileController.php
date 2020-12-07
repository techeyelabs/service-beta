<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ApiErrorCodes;
use App\Models\Profile;
use App\Models\Portfolio;
use App\Models\Residential;
use App\Libraries;
use App\Libraries\Common;
use App\Mail;
use DB;

class ProfileController extends Controller
{
    public function randomPrefix($length)
    {
        $random= "";
        $rand = rand(0,9999);
        $data = $rand."ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

        for($i = 0; $i < $length; $i++)
        {
            $random .= substr($data, (rand()%(strlen($data))), 1);
        }

        return $random;
    }
    public function configure(Request $request)
    {
        $user = $request->id;
        $user_name = $request->username;
        $phone = isset($request->phone)? $request->phone: '';
        $email = isset($request->email)? $request->email: '';
        $credit_card = isset($request->credit_card)? $request->credit_card: '';
        $transfer_account = isset($request->t_account)? $request->t_account: '';
        if($phone=='' || $email=='' || $user_name=='' || $credit_card=='' || $transfer_account==''){
            $e = ApiErrorCodes::where('error_code',10042)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            User::where('status', 'ACTIVE')
                ->where('id', $user)
                ->update(
                    [
                        'phone' => $phone, 
                        'email' => $email,
                        'first_name' => $user_name,
                        'credit_card' => $credit_card,
                        'transfer_account' => $transfer_account
                    ]
                );
            $e = ApiErrorCodes::where('error_code',10043)->first(); 
            $data = [
                'status'  => 200  ,
                'message' => $e->error_message
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function change_password(Request $request)
    {
        $user = isset($request->user_id)? $request->user_id: '';
        $password_old = isset($request->password_old)? sha1($request->password_old): '';
        $password_new = isset($request->password_new)? sha1($request->password_new): '';
        $is_exist = User::where('id', $user)->where('password', $password_old)->first(); 
        if($user=='' || $password_old=='' || $password_new==''){
                $e = ApiErrorCodes::where('error_code',10025)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
        } else if($is_exist!='' && $is_exist!=null) {
                User::where('status', 'ACTIVE')
                    ->where('id', $user)
                    ->update(
                        [
                            'password' => $password_new
                        ]
                    );
                $e = ApiErrorCodes::where('error_code',10043)->first(); 
                $data = [
                    'status'  => 200  ,
                    'message' => $e->error_message
                ];
        } else {
                $e = ApiErrorCodes::where('error_code',10023)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function update_basic(Request $request)
    {
        $ip = \Config::get('app.api_base_url');
        //Configuration
        $username = isset($request->username)?$request->username: ' ';
        $phone = isset($request->phone)?$request->phone: ' ';
        $email = isset($request->email)?$request->email: ' ';
        $creditcard = isset($request->creditcard)?$request->creditcard: ' ';
        $taccount = isset($request->t_account)?$request->t_account: ' ';
        $bankname = isset($request->t_account)?$request->bank_name: ' ';
        $branchname = isset($request->t_account)?$request->branch_name: ' ';
        $accountname = isset($request->t_account)?$request->account_name: ' ';
        $accounttype = isset($request->t_account)?$request->account_type: ' ';
        if($request->account_type == 1){
            $accounttype = 'general';
        } else if($request->account_type == 2){
            $accounttype = 'current';
        } else {
            $accounttype = 'savings';
        }
        //Configuration ends
        //Basics
        $user_id = $request->user_id;
        $profession = $request->profession;
        $area = $request->area;
        $address = isset($request->address)?$request->address: '';
        $sex = $request->sex;
        $photo_id = isset($_FILES['photo_id']['name'])?$_FILES['photo_id']['name']: '';
        $profile_pic = isset($_FILES['profile_picture']['name'])?$_FILES['profile_picture']['name']: '';
        $utility = isset($_FILES['utility']['name'])?$_FILES['utility']['name']: '';
        $updated_at = date('Y-m-d H:i:s');
        $ID_name = time().$photo_id;
        $ut_name = time().$utility;
        //Basic ends

            //Update 
            if($user_id!='' && $user_id!=null){
                if($photo_id!='' && $photo_id!=null){
                    move_uploaded_file($_FILES['photo_id']['tmp_name'],"assets/images/photo_id/".$ID_name);
                    Profile::where('user_id', $user_id)
                        ->update(
                            [
                                'photo_id' => $ID_name
                            ]
                        );
                }
                if(!empty($_FILES['utility']['tmp_name'])){
                    move_uploaded_file($_FILES['utility']['tmp_name'],"assets/images/utility/".$ut_name);
                    Profile::where('user_id', $user_id)
                        ->update(
                            [
                                'utility' => $ut_name
                            ]
                        );
                }
                
               
                if($profile_pic!='' || $profile_pic!=null){
                    move_uploaded_file($_FILES['profile_picture']['tmp_name'],"assets/images/users/".$profile_pic);
                    Profile::where('user_id', $user_id)
                        ->update(
                            [
                                'profile_image' => $profile_pic
                            ]
                        );
                    User::where('id', $user_id)
                        ->update(
                            [
                                'profile_pic' => $profile_pic
                            ]
                        );
                } 
                Profile::where('user_id', $user_id)
                ->update(
                    [
                        'profession' => ($profession==null)?' ': $profession,
                        'residential_area' => $area,
                        'address' => ($address==null)?' ': $address,
                        'sex' => $sex,
                        'updated_at' => $updated_at
                    ]
                );

                User::where('id', $user_id)
                        ->update(
                            [
                                'first_name' => ($username==null)?' ': $username,
                                'phone' => ($phone==null)?' ': $phone,
                                'email' => ($email==null)?' ': $email,
                                'credit_card' => ($creditcard==null)?' ': $creditcard,
                                'transfer_account' => ($taccount==null)?' ': $taccount,
                                'bank_name' => ($bankname==null)?' ': $bankname,
                                'branch_name' => ($branchname==null)?' ': $branchname,
                                'account_name' => ($accountname==null)?' ': $accountname,
                                'account_type' => ($accounttype==null)?' ': $accounttype
                            ]
                        );
                $latest_image = Profile::where('user_id', $user_id)->first();
                $e = ApiErrorCodes::where('error_code',10043)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message,
                    'profile_pic' => $ip.'assets/images/users/'.$latest_image->profile_image
                ];
                // $priceid =  $u->id;
            } else {
                $e = ApiErrorCodes::where('error_code',10049)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function get_my_basic($id=null)
    {
         /**************************
            @Name: Fetch basic profile info
            @URL: get-basic-profile
            @Method: GET
            @Params: seller id
        ***************************/
        $ip = \Config::get('app.api_base_url');
        $user_id = $id;
        if($user_id!='' || $user_id!=null){
            // $basics = DB::select("SELECT 
            //                 pro.*,
            //                 CONCAT('".$ip."assets/images/users/','',pro.profile_image) AS img
            //                 FROM profile AS pro
            //                 WHERE pro.user_id = '".$user_id."'");
            $basics = DB::table('profile AS pro')
                            ->select('pro.*', 'us.first_name', 'sex.description AS sx', 'ra.area',
                                    DB::raw('CONCAT("'.$ip.'","assets/images/users/", pro.profile_image) AS img'),
                                    DB::raw('CONCAT("'.$ip.'","assets/images/photo_id/", pro.photo_id) AS pid'),
                                    DB::raw('CONCAT("'.$ip.'","assets/images/utility/", pro.utility) AS ut')
                                    )
                            ->where('pro.user_id', $user_id)
                            ->leftjoin('users AS us', 'us.id', '=', 'pro.user_id')
                            ->leftjoin('sex', 'sex.id', '=', 'pro.sex')
                            ->leftjoin('residential_area AS ra', 'ra.id', '=', 'pro.residential_area')
                            ->first();
            $data = [
                'status'=> 200,
                'payloads' => $basics
            ];
        } else {
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function update_my_text(Request $request)
    {
        $ip = \Config::get('app.api_base_url');
        if(!empty($_FILES['file']['name'])){
            $file =  $_FILES['file']['name'];
        }
        $filename = '';
        $user_id = $request->user_id;
        $filename = isset($file)?time().$file:'';
        $username = isset($request->username)?$request->username:'';
        $content = isset($request->personal_content)?$request->personal_content:'';
        $updated_at = date('Y-m-d H:i:s');
        if($user_id==''){
            $e = ApiErrorCodes::where('error_code',10070)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            if(!empty($_FILES['file']['name'])){
                move_uploaded_file($_FILES['file']['tmp_name'],"assets/images/users/".$filename);
            }           
            if($content!=''){
                Profile::where('user_id', $user_id)
                ->update(
                    [
                        'personal_details' => $content,
                        'updated_at' => $updated_at
                    ]
                );
            }
            if($filename!=''){
                Profile::where('user_id', $user_id)
                ->update(
                    [
                        'profile_image' => $filename,
                        'updated_at' => $updated_at
                    ]
                );
                User::where('id', $user_id)
                ->update(
                    [
                        'profile_pic' => $filename,
                        'updated_at' => $updated_at
                    ]
                );
            }
            if($username!=''){
                User::where('id', $user_id)
                ->update(
                    [
                        'first_name' => $username,
                        'updated_at' => $updated_at
                    ]
                );
            }    
            $latest_image = Profile::where('user_id', $user_id)->first();       
            $e = ApiErrorCodes::where('error_code',10043)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message,
                'profile_pic' => $ip.'assets/images/users/'.$latest_image->profile_image
            ];
        } 
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function get_my_personal_text($id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $user = $id;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10070)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $personal = DB::table('profile AS pro')
                            ->select('pro.id', 'pro.user_id', 'pro.personal_details',
                                DB::raw('CONCAT("'.$ip.'","assets/images/users/", pro.profile_image) AS img'), 'pro.profile_image', 'us.first_name')
                            ->leftjoin('users AS us', 'us.id', '=', 'pro.user_id')
                            ->where('pro.user_id', $user)
                            ->first();
            $data = [
                'status'  => 200,
                'personal' => $personal
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    /**************************
        @Name: Add new item in portfolio
        @URL: get-basic-profile
        @Method: GET
        @Params: seller id
    ***************************/
    public function add_portfolio(Request $request)
    {
        $user = $request->username;
        $filename = '';
        if(!empty($_FILES['file']['name'])){
            $file =  $_FILES['file']['name'];
        }
        $filename = isset($file)?time().$file:'';
        $category = $request->category;
        $title = $request->title;
        $details = $request->details;
        $date = $request->date;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10070)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if(empty($_FILES['file']['name'])){
            $e = ApiErrorCodes::where('error_code',10070)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            if(move_uploaded_file($_FILES['file']['tmp_name'],"assets/images/portfolio/".$filename)){
                $port = new Portfolio();
                $port->seller_id = $user;
                $port->service_image = $filename;
                $port->category = $category;
                $port->title = $title;
                $port->details = $details;
                $port->status = 'ACTIVE';
                $port->production_date = $date;
                $port->seller_id = $user;
                $port->seller_id = $user;
                $port->save();
                $e = ApiErrorCodes::where('error_code',10043)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            } else {
                $e = ApiErrorCodes::where('error_code',10070)->first(); 
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

    /**************************
        @Name: Get service listing
        @URL: get-service-profile
        @Method: GET
        @Params: seller id
    ***************************/
    public function get_my_services($id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $seller = $id;
        if($seller=='' || $seller==null){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $services = DB::table('products AS pro')
                        ->select('pro.id', 'pro.title',
                                    DB::raw('CONCAT("'.$ip.'","assets/images/products/", pro.image_name) AS img'))
                        ->where('pro.seller_id', $seller)
                        ->orderBy('pro.id', 'DESC')
                        ->limit(4)
                        ->get();
            $data = [
                'status'  => 200,
                'payload' => $services
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8'); 
    }

     /**************************
        @Name: Get portfolio items
        @URL: get-portfolio-profile
        @Method: GET
        @Params: seller id
    ***************************/
    public function get_my_portfolio($id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $seller = $id;
        if($seller=='' || $seller==null){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $services = DB::table('portfolio AS por')
                        ->select('por.id', 'por.title',
                                DB::raw('CONCAT("'.$ip.'","assets/images/portfolio/", por.service_image) AS img'))
                        ->where('por.seller_id', $seller)
                        ->orderBy('por.id', 'DESC')
                        ->limit(4)
                        ->get();
            $data = [
                'status'  => 200,
                'payload' => $services
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8'); 
    }

    /**************************
        @Name: Get profile details
        @URL: get-profile
        @Method: GET
        @Params: user id
    ***************************/
    public function get_profile_info($id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $user = $id;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $profile = DB::table('profile AS prof')
                        ->select('prof.*', 'x.description', 'ra.area',  DB::raw('CONCAT("'.$ip.'","assets/images/users/", prof.profile_image) AS img'))
                        ->leftjoin('sex AS x', 'prof.sex', '=', 'x.id')
                        ->leftjoin('residential_area AS ra', 'prof.residential_area', '=', 'ra.id')
                        ->where('prof.user_id', $user)
                        ->first();
            $personal = DB::table('profile AS pro')
                        ->select('pro.id', 'pro.user_id', 'pro.personal_details',
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", pro.profile_image) AS img'), 'pro.profile_image', 'us.first_name')
                        ->leftjoin('users AS us', 'us.id', '=', 'pro.user_id')
                        ->where('pro.user_id', $user)
                        ->first();
            $data = [
                'status'  => 200,
                'profile' => $profile,
                'personal' => $personal,
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8'); 
    }

     /**************************
        @Name: Get profile details
        @URL: get-profile
        @Method: GET
        @Params: user id
    ***************************/
    public function get_portfolio_all($id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $user = $id;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $portfolio = DB::table('portfolio AS port')
                            ->select('port.*', 'cat.cat_name', DB::raw('SUBSTRING(port.details, 1, 100) AS trimmed_details'),
                                    DB::raw('CONCAT("'.$ip.'","assets/images/portfolio/", port.service_image) AS img'))
                            ->leftjoin('category AS cat', 'cat.id', '=', 'port.category')
                            ->where('port.seller_id', $user)
                            ->where('port.status', 'ACTIVE')
                            ->orderBy('port.id', 'DESC')
                            ->get();
            $data = [
                'status'  => 200,
                'payload' => $portfolio
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8'); 
    }
     /**************************
        @Name: Fetch configuration info
        @URL: get-config-profile
        @Method: GET
        @Params: user id
    ***************************/
    public function get_configure($id)
    {
        $user = $id;
        if($user=='' || $user==null){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $conf = User::select('id', 'first_name', 'email', 'phone', 'credit_card', 'transfer_account', 'branch_name', 'bank_name', 'account_name', 'account_type')
                            ->where('id', $user)
                            ->first();
            $data = [
                'status'  => 200  ,
                'payload' => $conf
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8'); 
    }

    public function check_username($name, $id)
    {
        if($id==0){
            $is_exist = User::where('first_name', $name)->first();
        } else {
            $is_exist = User::where('first_name', $name)->where('id', '!=' , $id)->first();
        }       
        if(!empty($is_exist)){
            $data = [
                'status'  => 200
            ];
        } else {
            $data = [
                'status'  => 404
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8'); 
    }

    public function i_forgot(Request $request)
    {
        $mail = $request->email;
        $is_exist = User::where('email', $mail)->where('status', 'ACTIVE')->first();
        if(empty($is_exist)){
            $data = [
                'status'  => 404
            ];
        } else {
            $prev_code = $is_exist->password_reset_code;
            if(strlen($prev_code) != 6){
                //Store password reset code
                $act_code = $this->randomPrefix(6);
                $is_exist->password_reset_code = $act_code;
                $is_exist->save();
                
                $prev_code = $act_code;
            }
            //Password reset mail
            $data = [
                'link' => 'https://share-work.jp/enter-new-password/'.$prev_code,
                'name' => $is_exist->first_name
            ];
            \Mail::send('passwordreset', $data, function($message) use ($request){
                $message->to($request->email, 'test')->subject
                   ('[share-work] パスワード再設定のご案内');
                $message->from('noreply@share-work.jp','ShareWork');
             });
            //Password reset mail ends

            $data = [
                'status'  => 200
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8'); 
    }

    public function save_pass(Request $request)
    {
        $p1 = $request->password1;
        $p2 = $request->password2;
        $code = $request->code;
        if($p1 != $p2){
            $data = [
                'status'  => 500,
                'type' => 0
            ];
        } else {
            $pass = sha1($p1);
            $us = User::where('password_reset_code', $code)->first();
            if(empty($us)){
                $data = [
                    'status'  => 404,
                    'type' => 0
                ];
            } else {
                $us->password_reset_code = '';
                $us->password = $pass;
                $us->save();

                $data = [
                    'status'  => 200,
                    'type' => $us->user_type_id
                ];
            }
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8'); 
    }

    public function check_link(Request $request)
    {
        $code = $request->code;
        $is_exist = User::where('password_reset_code', $code)->first();
        if(empty($is_exist)){
            $data = [
                'status'  => 404
            ];
        } else {
            $data = [
                'status'  => 200
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8'); 
    }
}
