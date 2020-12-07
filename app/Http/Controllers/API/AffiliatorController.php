<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApiErrorCodes;
use App\Models\Chat;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserPointWithdrawal;
use App\Models\AffiliatorPersonalPage;
use DB;

class AffiliatorController extends Controller
{
    public function randomPrefix($length)
    {
        $random= "";
        $rand = rand(0,9999);
        $data = $rand."ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";

        for($i = 0; $i < $length; $i++)
        {
            $random .= substr($data, (rand()%(strlen($data))), 1);
        }

        return $random;
    }
    public function store(Request $request)
    {
        echo json_decode($request);
        exit;
        // $test_data = DB::table('units')->get();
        // return response($test_data)->header('Content-type','application/json');
    }
    public function aff_log(Request $request)
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
            $is_exist = User::where('email', $email)->where('password', $pass)->where('user_type_id', 3)->first(); 
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
                        'img_sidebar'=> $ip  ."assets/images/users/" .  $is_exist->profile_pic,  
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

    public function affiliator_reg(Request $request)
    {
        $aff_url = env('AFFILIATOR_BASE_URL');
        // $aff_url = 'http://192.168.1.23:3000/';
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
        else if($request->ref_code=='' || $request->ref_code==null){
            $e = ApiErrorCodes::where('error_code',10054)->first(); 
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
                    $parent_ref = $request->ref_code;
                    $parent = User::where('ref_code',$parent_ref)->first();
                    $u = new User();
                    $u->email = $request->email;
                    $u->first_name = $request->name;
                    $u->password = sha1($request->password);
                    $u->user_type_id = 3;
                    $u->parent_id = $parent->id;
                    $u->status = 'ACTIVE';
                    $u->level_in_tree = $parent->level_in_tree + 1;
                    $u->profile_pic = $filename;
                    $u->save();
                    $last_insert_id =  $u->id;
                    $datetime = date('Ymd',strtotime($u->created_at));
                    $ran = $this->randomPrefix(10);
                    $ref = $last_insert_id.$datetime.$ran;
                    $link = $request->name.'/'.$ref;
                    User::where('id', $last_insert_id)->update(['ref_code' => $ref, 'affiliator_links_id' => $link]);
                    //Profile table update
                    $is_exist = Profile::where('user_id', $last_insert_id)->count();
                    if($is_exist==0){
                        $pro = new Profile();
                        $pro->user_id = $last_insert_id;
                        $pro->profile_image = $filename;
                        $pro->save();

                        $pw = new UserPointWithdrawal();
                        $pw->user_id = $last_insert_id;
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

    /**************************
        @Name: Add products into personal list 
        @URL: backend/add-my-queue
        @Method: GET
        @params: Product ID
    ***************************/
    public function add_to_my_queue(Request $request)
    {
        $line_link = $request->line_link;
        $web_link = $request->web_link;
        $product = $request->pro_id;
        $affiliator = $request->aff_id;
        if($product==null || $product=='' || $affiliator==null || $affiliator==''){
            $e = ApiErrorCodes::where('error_code',10052)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ];
        } else {
            $is_exist = AffiliatorPersonalPage::where('product_id', $product)->where('affiliator_id', $affiliator)->get();
            if(count($is_exist)>0){
                $e = ApiErrorCodes::where('error_code',10063)->first(); 
                $data = [
                    'status'  => $e->error_code,
                    'message' => $e->error_message
                ];
            } else {
                $aff_page = new AffiliatorPersonalPage();
                $aff_page->affiliator_id = $affiliator;
                $aff_page->product_id = $product;
                $aff_page->line_link = $line_link;
                $aff_page->web_link = $web_link;
                $aff_page->created_at = date('Y-m-d H:i:s');
                $aff_page->updated_at = date('Y-m-d H:i:s');
                $aff_page->save();

                //Generate response
                $e = ApiErrorCodes::where('error_code',10051)->first(); 
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

    /**************************
        @Name: Add products into personal list 
        @URL: backend/add-my-queue
        @Method: GET
        @params: Product ID
    ***************************/
    public function get_aff_id($aff_name=null)
    {
        $affiliator = $aff_name;
        if($affiliator=='' || $affiliator==null){
            $e = ApiErrorCodes::where('error_code',10051)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ];
        } else {
            $aff_all = User::where('id', $affiliator)->first();
            if(empty($aff_all)){
                $e = ApiErrorCodes::where('error_code',10069)->first(); 
                $data = [
                    'status'  => $e->error_code,
                    'message' => $e->error_message
                ];
            } else {
                $id = $aff_all->id;
                $data = [
                    'status'  => 200,
                    'message' => $id
                ];
            }
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    /**************************
        @Name: Search descendants 
        @URL: backend/get-particular-child
        @Method: POST
    ***************************/
    public function get_particular_child(Request $request)
    {
        $searchkey = isset($request->searchkey)?$request->searchkey:'';
        $affiliator = isset($request->aff)?$request->aff:'';
        $decendants = DB::select("SELECT 
                                id, 
                                first_name, 
                                parent_id, 
                                level_in_tree,
                                CASE level_in_tree
                                    WHEN 1 THEN 14
                                    WHEN 2 THEN 10
                                    WHEN 3 THEN 4
                                    WHEN 4 THEN 4
                                    WHEN 5 THEN 2
                                    WHEN 6 THEN 2
                                    WHEN 7 THEN 2
                                    WHEN 8 THEN 2
                                ELSE NULL
                                    END as 'commission'
                                FROM (SELECT * from users WHERE first_name LIKE '%$searchkey%' ORDER BY parent_id, id) users_sorted, 
                                (SELECT @pv := ".$affiliator.") initialisation 
                                WHERE find_in_set(parent_id, @pv) AND length(@pv := concat(@pv, ',', id))
                                ");
        $data = [
            'status'  => 200,
            'payload' => $decendants
        ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }


    public function universal_link(Request $request)
    {
        $code = $request->code;
        if($code=='' || $code==null){
            $e = ApiErrorCodes::where('error_code',10077)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ];
        } else {
            $aff = User::select('id')->where('ref_code', $code)->first();
            if($aff=='' || $aff==null){
                $e = ApiErrorCodes::where('error_code',10077)->first(); 
                $data = [
                    'status'  => $e->error_code,
                    'message' => $e->error_message
                ];
            } else {
                $data = [
                    'status'  => 200,
                    'id' => $aff->id
                ];
            }
           
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
}
