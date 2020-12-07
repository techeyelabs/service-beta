<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiErrorCodes;
use App\Models\RequestBoard;
use App\Models\Budget;
use App\Models\RequestResponse;
use App\Models\Product;
use App\Models\Chat;
use App\Models\Category;
use App\Models\User;
use App\Models\ProductPrice;
use App\Models\ProductImage;
use App\Models\PurchaseHistory;
use App\Models\UserPointWithdrawal;
use App\Models\SystemBalance;
use App\Models\AffiliatorEarning;
use App\Models\ImmediateHolding;
use App\Models\Notification;
use App\Libraries\Affiliator;
use DB;

class RequestController extends Controller
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

    public function get_parent($id=null)
    {
        $affiliator_id = $id;
        if($affiliator_id==0)
            return 0;
        $parent = User::where('id', $affiliator_id)->first();
        if(empty($parent))
            return 0;
        $parent_id = $parent->parent_id;
        return $parent_id;
    }
    public function store(Request $request)
    {
        $product_id = isset($request->product_id)? $request->product_id: 0;
        $buyer_id = isset($request->buyer_id)? $request->buyer_id: '';
        $category = isset($request->category)? $request->category: 0;
        // $sub_category = isset($request->sub_category)? $request->sub_category: 0;
        $title = isset($request->title)? $request->title: '';
        $content = isset($request->content)? $request->content: '';
        $request_date_cost = isset($request->request_date_cost)? $request->request_date_cost: '';
        // $application_date = isset($request->application_date)? $request->application_date: '';
        // $budget = isset($request->budget)? $request->budget: '';
        $current_status = 'UNDERESTIMATION';
        // $created_at = date('Y-m-d H:i:s');
        // $updated_at = date('Y-m-d H:i:s');

        if($buyer_id=='' || $buyer_id==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        } else if($title=='' || $title==null) {
            $e = ApiErrorCodes::where('error_code',10031)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        } else if($content=='' || $content==null) {
            $e = ApiErrorCodes::where('error_code',10045)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        } else {
            //Get budget range
            // $bgt = Budget::where('id', $budget)->first();
            // if($bgt=='' || $bgt==null){
            //     $budget_range = 0;
            // } else {
            //     $budget_range = $bgt->budget_range;
            // }
            

            //Redundant
            if($product_id!='' && $product_id!=null){
                $pro_all = DB::table('products')->where('id', $product_id)->first();
                $seller = $pro_all->seller_id;
            } else {
                $seller = 0;
            }
            


            $acceptance_flag = ($product_id > 0)? 'ACCEPTED': 'INITIATED';
            $u = new RequestBoard();
            $u->buyer_id = $buyer_id;
            $u->accepted_seller_id = $seller;
            $u->product_id = $product_id;
            $u->category = $category;
            $u->title = $title;
            $u->content = $content;
            $u->req_date_cost = $request_date_cost;
            $u->acceptance_status = $acceptance_flag;
            $u->save();

            //Get seller id
            if($product_id!='' && $product_id!=null){
                $buyer_name = DB::table('users')->select('first_name')->where('id', $buyer_id)->first();
                $pro_all = DB::table('products')->where('id', $product_id)->first();
                $seller = $pro_all->seller_id;
                $sender = $buyer_id;
                $receiver = $seller;
                $buyer = $buyer_id;

                //Generate notification for seller
                $buyer_info = User::select('first_name')->where('id', $buyer_id)->first();
                $not = new Notification();
                $not->user_id = $seller;
                $not->product_id = $product_id;
                $not->request_id = $u->id;
                $not->response_id = 0;
                $not->seller_id = $seller;
                $not->buyer_id = $buyer_id;
                $not->notification_text = $buyer_info->first_name.' さんから '.$pro_all->title.' の見積り・カスタマイズの相談きました。';
                $not->opening_status = 'UNOPENED';
                $not->notification_type = 'REQUESTRECEIVED';
                $not->save();
                //Notification generation ends
            } else {
                $seller = 0;
            }
            //

            $data = [
                'status'  => 200  ,
                'message' => 'Request successfully uploaded'
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function myRequestBoard($id=null)
    {
        $myid = $id;
        if($myid=='' || $myid==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];
            return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
        } else {
            // $is_exist = RequestBoard::where('buyer_id', $myid)->get(); 
            // , 'budget.budget_range AS budget_amount'
            $is_exist = DB::table('request_board')
                ->select('request_board.*', 'request_board.number_of_proposals')
                // ->join('budget', 'request_board.budget', '=', 'budget.id')
                ->where('request_board.buyer_id', $myid)
                ->where('request_board.current_status', 'UNDERESTIMATION')
                ->whereIn('request_board.acceptance_status', ['INITIATED','ACCEPTED'])
                // ->where('request_board.application_date', '>=' , date('Y-m-d').' 00:00:00')
                // ->orderBy('request_board.application_date', 'ASC')
                ->get();
            if($is_exist!=null && $is_exist!=''){
                $data = [
                    'status'  => 200,
                    'payload' => $is_exist
                ];
            } else {
                $e = ApiErrorCodes::where('error_code',10041)->first(); 
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

    public function get_budget_list()
    {
        $e = Budget::all(); 
        if(!empty($e)){
            $data = [
                'status'  => 200,
                'message' => $e
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

    public function get_all_requests_i_responded_to($user=null, $layer=null)
    {
        $ip = \Config::get('app.api_base_url');
        $seller = $user;
        $leave = ($layer-1)*10; 
        // $d = new Date();
        $requests = DB::table('request_response AS rr')
                        ->select('rb.*', 'rr.estimated_price as request_price', 'rb.number_of_proposals','rr.created_at as response_date',
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img'))
                        ->skip($leave)->take(10)
                        ->leftjoin('request_board AS rb', 'rb.id', '=', 'rr.request_id')
                        // ->leftjoin('budget as b', 'b.id', '=', 'rb.budget')
                        ->leftjoin('users as us', 'us.id', '=', 'rb.buyer_id')
                        ->where('rb.current_status', 'UNDERESTIMATION')
                        ->whereIn('rb.acceptance_status', ['INITIATED','ACCEPTED','REJECTED'])
                        // ->where('rb.acceptance_status', 'ACCEPTED')
                        ->where('rr.seller_id', $seller)
                        ->where('rb.product_id', '=', 0)
                        // ->where('rr.created_at', '>=', date('Y-m-d').' 00:00:00')
                        ->groupBy('rr.request_id')
                        ->orderBy('rr.created_at', 'ASC')
                        ->get(); 
        $total = DB::table('request_response AS rr')
                ->select('rr.id')
                ->leftjoin('request_board AS rb', 'rb.id', '=', 'rr.request_id')
                ->where('rb.current_status', 'UNDERESTIMATION')
                ->where('rr.seller_id', $seller)
                ->where('rr.created_at', '>=', date('Y-m-d').' 00:00:00')
                ->count();
        if(empty($requests)){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200  ,
                'payload' => $requests,
                'total' => $total
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function bulletin_board($user=null, $layer=null)
    {
        $ip = \Config::get('app.api_base_url');
        $seller = $user;
        $let = 10;
        if($layer==null){
            $leave =0;
        } else{
            $leave = ($layer-1)*10;
        }
        $date = date('Y-m-d');
        $requests = DB::table('request_board')
                        ->select('request_board.*','cat.cat_name',
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img'))
                        ->skip($leave)->take(10)
                        ->leftjoin('users as us', 'us.id', '=', 'request_board.buyer_id')
                        ->join('category as cat', 'cat.id', '=', 'request_board.category')
                        ->where('request_board.buyer_id', '!=', $seller)
                        ->where('request_board.current_status', 'UNDERESTIMATION')
                        ->Where('request_board.accepted_seller_id', '=' , '')
                        ->orderBy('request_board.created_at','asc')
                        // ->Where('request_board.application_date', '>=' , date('Y-m-d').' 00:00:00')
                        // ->orderBy('request_board.application_date', 'ASC')
                        ->get(); 
        // $total = RequestBoard::where('buyer_id', '!=', $seller)->where('current_status', 'UNDERESTIMATION')->count();
        /*$requests = DB::table('request_response AS rr')
                        ->select('rb.*', 'b.budget_range', 'rr.seller_id',
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img'))
                        ->skip($leave)->take(10)
                        ->leftjoin('request_board AS rb', 'rb.id', '=', 'rr.request_id')
                        ->leftjoin('budget as b', 'b.id', '=', 'rb.budget')
                        ->leftjoin('users as us', 'us.id', '=', 'rb.buyer_id')
                        ->where('rb.current_status', 'UNDERESTIMATION')
                        ->where('rr.seller_id', '!=', $seller)
                        // ->groupBy('rr.request_id')
                        ->get(); */


        // $requests = DB::select(DB::raw("select * from (SELECT * FROM `request_board` WHERE buyer_id!=$seller and product_id=0 and current_status='UNDERESTIMATION') as t
        //                         where NOT EXISTS(SELECT id FROM `request_board` WHERE buyer_id!=$seller and product_id=0 and current_status='UNDERESTIMATION') OR  
        //                         EXISTS(SELECT id FROM `request_board` WHERE buyer_id!=$seller and product_id=0 and current_status='UNDERESTIMATION') ORDER BY id DESC LIMIT $leave, $let"));
        
        /*DB::table('request_response AS rr')
                        ->select('rb.*', 'b.budget_range', 'rr.seller_id',DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img'))
                        ->where('rb.buyer_id' , '!=' , $seller )
                        ->where('rb.product_id' ,'=', 0 )
                        ->where('rb.current_status', 'UNDERESTIMATION')
                        ->skip($leave)->take(10)
                        ->get();*/

        //dd($requests );
        // return response($requests)
        //     ->header('Content-type','application/json')
        //     ->header('charset', 'utf-8');
        //             exit();

        // $total = DB::table('request_response AS rr')
        //         ->select('rr.id')
        //         ->leftjoin('request_board AS rb', 'rb.id', '=', 'rr.request_id')
        //         ->where('rb.current_status', 'UNDERESTIMATION')
        //         ->where('rr.seller_id', $seller)
        //         ->Where('rb.application_date', '>=' , date('Y-m-d').' 00:00:00')
        //         ->count();
        $total = DB::table('request_board AS rr')
                ->select('rr.id')
                ->where('rr.current_status', 'UNDERESTIMATION')
                ->count();
        if(empty($requests)){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $data = [
                'status'  => 200  ,
                'payload' => $requests,
                'total' => $total
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
     /**************************
        @Name: Details request info
        @URL: backend/purchase
        @Method: GET
        @Params: Request ID
    ***************************/
    public function get_request_detail($id=null, $user=null)
    {
        $ip = \Config::get('app.api_base_url');
        $req = $id;
        $seller = $user;
        if($req=='' || $req==null){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $request = DB::table('request_board')
                        ->select('request_board.*', 'request_board.created_at',
                                DB::raw('CONCAT("'.$ip.'","assets/images/users/", u.profile_pic) AS img'), 
                                'c.cat_name AS cat', 'sub_c.cat_name AS sub_cat', 'u.first_name AS buyer_name',
                                'pro.title AS pro_title', 'pro.short_desc', 'pro.long_desc')
                        // ->leftjoin('budget as b', 'b.id', '=', 'request_board.budget')'b.budget_range'
                        ->leftjoin('users as u', 'u.id', '=', 'request_board.buyer_id')
                        ->leftjoin('category as c', 'c.id', '=', 'request_board.category')
                        ->leftjoin('category as sub_c', 'sub_c.id', '=', 'request_board.sub_category')
                        ->leftjoin('products AS pro', 'pro.id', '=', 'request_board.product_id')
                        ->where('request_board.id', $req)
                        ->first(); 
            $cat_for_list = $request->category;
            $request_all_cat = DB::table('request_board')
                        ->select('request_board.*',
                                DB::raw('CONCAT("'.$ip.'","assets/images/users/", u.profile_pic) AS img'), 
                                'c.cat_name AS cat', 'sub_c.cat_name AS sub_cat', 'u.first_name AS buyer_name')
                        // ->leftjoin('budget as b', 'b.id', '=', 'request_board.budget')  'b.budget_range', 
                        ->leftjoin('users as u', 'u.id', '=', 'request_board.buyer_id')
                        ->leftjoin('category as c', 'c.id', '=', 'request_board.category')
                        ->leftjoin('category as sub_c', 'sub_c.id', '=', 'request_board.sub_category')
                        ->where('request_board.category', $cat_for_list)
                        ->get(); 
            $count = DB::table('request_response')
                        ->select('*')
                        ->where('seller_id', $seller)
                        ->where('request_id', $req)
                        ->count();
            if($count>0){
                $existing_res = DB::table('request_response AS rr')
                        ->select('rr.*', 'us.first_name AS seller')
                        ->where('seller_id', $seller)
                        ->where('request_id', $req)
                        ->leftjoin('users AS us', 'us.id', '=', 'rr.seller_id')
                        ->get();
            } else {
                $existing_res = '';
            }
            $data = [
                'status'  => 200  ,
                'payload' => $request,
                'requestall' => $request_all_cat,
                'count' => $count,
                'existing_response' => $existing_res
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function store_response(Request $request)
    {
        $request_id = $request->request_id;
        $seller_id = $request->seller_id;
        $content = $request->content;
        $estimated_price = $request->estimated_price;
        // $estimated_deadline = $request->estimated_deadline;
        if($request_id=='' || $request_id==null){
            $e = ApiErrorCodes::where('error_code',10058)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($seller_id=='' || $seller_id==null){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($content=='' || $content==null){
            $e = ApiErrorCodes::where('error_code',10045)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($estimated_price=='' || $estimated_price==null){
            $e = ApiErrorCodes::where('error_code',10056)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } 
        // else if($estimated_deadline=='' || $estimated_deadline==null){
        //     $e = ApiErrorCodes::where('error_code',10057)->first(); 
        //     $data = [
        //         'status'  => $e->error_code  ,
        //         'message' => $e->error_message
        //     ];
        // } 
        else {
            //Status changing of previous responses
            RequestResponse::where('request_id', $request->request_id)->where('seller_id', $request->seller_id)
            ->update(
                [
                    'status' => 'PREV'
                ]
            );
            //Latest response entry
            $x = new RequestResponse();
            $x->content = $request->content;
            $x->estimated_price = $request->estimated_price;
            // $x->estimated_deadline = $request->estimated_deadline;
            $x->request_id = $request->request_id;
            $x->seller_id = $request->seller_id;
            $x->status = 'LAST';
            $x->save();
            
            $is_exist = RequestBoard::select('*')->where('id', $request->request_id)->first();
            if($is_exist->product_id > 0){
                $path = 'TRANSACTION';
            } else {
                $path = 'LIST';
            }
            $is_excepted = RequestBoard::where('id', $request->request_id)->where('accepted_seller_id', $request->seller_id)->first();
            if(!empty($is_excepted)){
                RequestBoard::where('id', $request->request_id)
                ->update(
                    [
                        'proposed_budget' => $request->estimated_price
                    ]
                );
            }
            $already_responded = RequestResponse::where('request_id', $request->request_id)->where('seller_id', $request->seller_id)->count();
            if($already_responded == 1){
                RequestBoard::where('id', $request->request_id)
                ->update(
                    [
                        'number_of_proposals' => $is_exist->number_of_proposals + 1
                    ]
                );
            }
            //Generate notification
            //Get seller name
            $seller = DB::table('users')->select('first_name')->where('id', $request->seller_id)->first();
            //Get product name
            if($is_exist->product_id > 0){
                $pro = DB::table('products')->select('title')->where('id', $is_exist->product_id)->first();
            }
            $not = new Notification();
            $not->user_id = $is_exist->buyer_id;
            $not->product_id = $is_exist->product_id;
            $not->request_id = $is_exist->id;
            $not->response_id = $x->id;
            $not->seller_id = $request->seller_id;
            $not->buyer_id = $is_exist->buyer_id;
            if($is_exist->product_id > 0){
                // $not->notification_text = $seller->first_name.' from '.$pro->title .' の見積り来ました';
                $not->notification_text = 'You have been received'.$pro->title .' from '.$seller->first_name;
            } else {
                $not->notification_text = 'You have been received a request proposal from '.$seller->first_name;
                // $not->notification_text = $seller->first_name.' さんからリクエストの提案きました';
            }          
            $not->opening_status = 'UNOPENED';
            $not->notification_type = 'NEWRESPONSE';
            $not->save();

            $e = ApiErrorCodes::where('error_code',10051)->first(); 
            $data = [
                'status'  => 200  ,
                'message' => $e->error_message,
                'path' => $path
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function get_for_estimation_page($id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $seller = $id;
        if($seller=='' || $seller==null){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => 200  ,
                'message' => $e->error_message
            ];
        } else {
            $reqs = DB::table('request_board')
                    ->select('request_board.*',  
                            DB::raw('CONCAT("'.$ip.'","assets/images/users/", u.profile_pic) AS img'), 
                            'u.first_name AS buyer_name', 
                            'b.budget_range')
                    ->leftjoin('users as u', 'u.id', '=', 'request_board.buyer_id')
                    ->leftjoin('budget as b', 'b.id', '=', 'request_board.budget')
                    ->where('accepted_seller_id', $seller)
                    ->where('current_status', '=', 'UNDERESTIMATION')
                    ->where('acceptance_status', '!=' ,'ACCEPTED')
                    ->orderBy('request_board.id', 'DESC')
                    ->get();
            $data = [
                'status'  => 200  ,
                'payload' => $reqs
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function get_range($id=null)
    {
        $range = Budget::select('budget_range')->where('id', $id)->first();
        $data = [
            'status'  => 200  ,
            'payload' => $range
        ];
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

     /**************************
        @Name: Details request info
        @URL: backend/purchase
        @Method: GET
        @Params: Request ID
    ***************************/
    public function get_all_response($id=null)
    {
        $ip = \Config::get('app.api_base_url');
        $request = $id;
        if($request=='' || $request==null){
            $e = ApiErrorCodes::where('error_code',10071)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $res = DB::table('request_response AS rr')
                        ->select('rr.*', 'us.first_name' , DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img_receiver'))
                        ->leftjoin('users AS us', 'us.id', '=', 'rr.seller_id')
                        ->where('rr.request_id', $request)
                        ->where('rr.status', 'LAST')
                        ->get();
            $req = DB::table('request_board AS rb')
                        ->select('rb.*', 'us.first_name', 'usb.first_name AS buyer' ,'us.email', 'cat.cat_name', 
                                'bgt.budget_range', 'pro.long_desc', 'pro.short_desc', 'pro.title AS pro_title')
                        ->leftjoin('users AS us', 'us.id', '=', 'rb.accepted_seller_id')
                        ->leftjoin('users AS usb', 'usb.id', '=', 'rb.buyer_id')
                        ->leftjoin('category AS cat', 'cat.id', '=', 'rb.category')
                        ->leftjoin('budget AS bgt', 'bgt.id', '=', 'rb.budget')
                        ->leftjoin('products AS pro', 'pro.id', '=', 'rb.product_id')
                        ->where('rb.id', $request)
                        ->first();
            $accepted = DB::table('request_board')
                        ->select('accepted_seller_id')
                        ->where('id', $request)
                        ->first();
            if($accepted=='' || $accepted==null){
                $id_acc = 0;
            } else {
                $id = DB::table('request_response AS rr')
                        ->select('rr.id')
                        ->where('request_id', $request)
                        ->where('seller_id', $accepted->accepted_seller_id)
                        ->where('status', 'LAST')
                        ->first();
                if(empty($id)){
                    $id_acc = 0;
                } else {
                    $id_acc = $id->id;
                }
            }
            $data = [
                'status'  => 200,
                'request' => $req,
                'response' => $res,
                'accepted_id' => $id_acc
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

     /**************************
        @Name: Accept response from seller
        @URL: backend/accept-requests-response
        @Method: POST
    ***************************/
    public function accept(Request $request)
    {
        $request_id = $request->request_id;
        $seller_id = $request->seller_id;
        $buyer_id = $request->buyer_id;
        if($request_id=='' || $request_id==null){
            $e = ApiErrorCodes::where('error_code',10071)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($seller_id=='' || $seller_id==null){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else if($buyer_id=='' || $buyer_id==null) {
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $is_exist = RequestBoard::where('id', $request_id)->where('buyer_id', $buyer_id)->first();
            if($is_exist->acceptance_status == 'ACCEPTED'){
                $e = ApiErrorCodes::where('error_code',10060)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            } else {
                $response = DB::table('request_response AS rr')
                            ->select('rr.estimated_price', 'rr.estimated_deadline', 'rr.id')
                            ->where('rr.request_id', $request_id)
                            ->where('rr.seller_id', $seller_id)
                            ->where('rr.status', 'LAST')
                            ->first();
                RequestBoard::where('id', $request_id)->where('buyer_id', $buyer_id)
                    ->update(
                        [
                            'accepted_seller_id' => $seller_id,
                            'proposed_budget' => $response->estimated_price,
                            'proposed_delivery_time' => $response->estimated_deadline,
                            'acceptance_status' => 'ACCEPTED'
                        ]
                    );
                    
                    $buyer_info = User::select('first_name')->where('id', $buyer_id)->first();
                    $service_name = Product::select('title')->where('id', $is_exist->product_id)->first();
                    //Generate notification
                    $not = new Notification();
                    $not->user_id = $seller_id;
                    $not->product_id = $is_exist->product_id;
                    $not->request_id = $request_id;
                    $not->response_id = 0;
                    $not->seller_id = $seller_id;
                    $not->buyer_id = $buyer_id;
                    if($service_name == '' || $service_name == null){
                        $not->notification_text = $buyer_info->first_name.' さんが提案を受け取れました';
                    } else {
                        $not->notification_text = $buyer_info->first_name.' さんが'.$service_name->title.' さんが提案を受け取れました';
                    }                  
                    $not->opening_status = 'UNOPENED';
                    $not->notification_type = 'RESPONSEACCEPTED';
                    $not->save();

                    $e = ApiErrorCodes::where('error_code',10051)->first(); 
                    $data = [
                        'status'  => 200,
                        'id' => $response->id
                    ];
            }           
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }


    public function get_filtered_reqs(Request $request)
    {
        $ip = \Config::get('app.api_base_url');
        $product = $request->product_name;
        $cat = $request->category;
        $requests = DB::table('request_board')
                        ->select('request_board.*', 'b.budget_range', DB::raw('CONCAT("'.$ip.'","assets/images/users/", us.profile_pic) AS img'))
                        ->leftjoin('budget as b', 'b.id', '=', 'request_board.budget')
                        ->leftjoin('users as us', 'us.id', '=', 'request_board.buyer_id')
                        ->when($request->product_name, function($query) use ($request){
                            return $query->where('request_board.title', 'LIKE', '%'.$request->product_name.'%');
                        })
                        ->when($request->category, function($query) use ($request){
                            return $query->where('request_board.category', $request->category);
                        })
                        ->orderBy('request_board.id', 'DESC')
                        ->get(); 
        $total = count($requests);
        $data = [
            'status'  => 200  ,
            'payload' => $requests,
            'total' =>$total
        ];
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }


    public function receive(Request $request)
    {
        $req = $request->request_id;
        if($req == '' || $req == null){
            $e = ApiErrorCodes::where('error_code',10058)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            RequestBoard::where('id', $req)
            ->update(
                [
                    'current_status' => 'COMPLETE',
                    'completion_date' => date("Y-m-d")
                ]
            );
            //Distribution process

            //Fetch data
            $all_from_request_board = RequestBoard::where('id', $req)->first();
            $product_id = $all_from_request_board->product_id;
            $buyer_id = $all_from_request_board->buyer_id;
            $seller_id = $all_from_request_board->accepted_seller_id;
            $payment_method_id = 1;
            $affiliator_id = $all_from_request_board->affiliator_id;

            $all = DB::table('products')->where('id', $product_id)->first();
            if($all){
                $seller_id = $all->seller_id;
            	$price_id = $all->price_id;
                $aff_commission = $all->affiliator_commission_amount;
                $commission_percent_index = $all->affiliator_commission_amount;
                $commission_all = DB::table('affiliator_points')->where('id', $all->affiliator_commission)->first();
                if(!empty($commission_all)){
                    $commission_percent = $commission_all->point_value;
                } else {
                    $commission_percent = 0;
                }              
            	$fee_flag = $all->commission_in_ex_vat;
            	// $vat = $all->commision_include_vat_amount;
            	$service_id = $all->service_id;
            	// Get price amount from price ID
            	$price_all = ProductPrice::where('id', $price_id)->first();
                $price = $all_from_request_board->proposed_budget;
                $vat = $price * (20/100);
            	//Fetch data ends
            	$cost = floor($price);

            	// Seller earning
            	$earning = $price - $vat;
                $earning = floor($earning);
                
                //title for noti
                $title_for_noti = $all->title;
            } else {
            	$seller_id = $all_from_request_board->accepted_seller_id;
                $price = $all_from_request_board->proposed_budget;
                $aff_commission = 0;
                $fee_flag = 'INCLUDE_VAT';
                $vat = $price*(20/100);
                $service_id = $all_from_request_board->id;
       			$cost = floor($price);

            	// Seller earning
            	$earning = $price - $vat;
                $earning = floor($earning);
                
                //title for noti
                $title_for_noti = $all_from_request_board->title;
            }
            
            $seller_info = User::select('first_name')->where('id', $all_from_request_board->accepted_seller_id)->first();
            $buyer_info = User::select('first_name')->where('id', $all_from_request_board->buyer_id)->first();
            //Generate notification
            $not = new Notification();
            $not->user_id = $all_from_request_board->accepted_seller_id;
            $not->product_id = $all_from_request_board->product_id;
            $not->request_id = $all_from_request_board->id;
            $not->response_id = 0;
            $not->seller_id = $all_from_request_board->accepted_seller_id;
            $not->buyer_id = $all_from_request_board->buyer_id;
            $not->notification_text =   $buyer_info->first_name.' さんが '.$title_for_noti.' を受け取れました';
            $not->opening_status = 'UNOPENED';
            $not->notification_type = 'SERVICERECEIVED';
            $not->save();

            // Transaction id generation (transaction_id pattern: TRXN-product_idUser_idtimerandom(of 3 digits))
            $ran = $this->randomPrefix(3);
            $transaction_id = 'TRXN-'.$req.$buyer_id.time().$ran;
            //Transcation ID generation ends

            // If affiliator is involved, calculation starts
                if($affiliator_id>0){
                    if($aff_commission>0){
                        //Seller earning after deductiong affiliator commission
                        $deductable_commission = ((100 - $commission_percent)/100)*($earning);
                        $seller_earning = $deductable_commission;
                        $aff_commission = (($commission_percent)/100)*($earning);
                        //External library usage
                        $Affiliator = new Affiliator();
	                    $Affiliator->commission($price, $affiliator_id, $commission_percent, $all_from_request_board->product_id);
                    } else {
                        //Product is affiliated but there is no commission for affiliator
                        $e = ApiErrorCodes::where('error_code',10062)->first(); 
                        $data = [
                            'status'  => $e->error_code  ,
                            'message' => $e->error_message
                        ];
                        return response()->json($data);
                    }
                } else {
                    // Affiliator calculation ends

                    // Seller earning in case of no affiliator commission
                    $seller_earning = $earning;

                     // Add tax and system fee into system
                    $prev_sys = SystemBalance::first();
                    SystemBalance::where('status', 'ACTIVE')
                        ->update(
                            [
                                'balance' => $prev_sys->balance + $vat*(60/100),
                                'tax' => $prev_sys->tax + $vat*(40/100)
                            ]
                        );
                }
                // Seller point add
                $prev = UserPointWithdrawal::where('user_id', $seller_id)->first();
                UserPointWithdrawal::where('status', 'ACTIVE')
                    ->where('user_id', $seller_id)
                    ->update(
                        [
                            'total_amount' => $prev->total_amount + $seller_earning,
                            'last_month_earnings' => $prev->last_month_earnings + $seller_earning,
                            'remaining_amount' => $prev->remaining_amount + $seller_earning
                        ]
                    );
                // Execution of the purchase
                $purchase = new PurchaseHistory();
                $purchase->product_id = $product_id;
                $purchase->seller_id = $seller_id;
                $purchase->buyer_id = $buyer_id;
                $purchase->service_id = $service_id;
                $purchase->price = $price;
                $purchase->aff_commission = ($affiliator_id>0)?$aff_commission: 0;
                $purchase->transaction_fee = $price*(20/100);
                $purchase->payment_method_id = $payment_method_id;
                $purchase->transaction_id = $transaction_id;
                $purchase->save();

            //Distribution ends here

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


    public function send(Request $request)
    {
        $req = $request->request_id;
        if($req == '' || $req == null){
            $e = ApiErrorCodes::where('error_code',10058)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            RequestBoard::where('id', $req)
            ->update(
                [
                    'trading_status' => 'SELLERSERVED',
                    'acceptance_status' => 'SERVED'
                ]
            );

            $req_all = RequestBoard::where('id', $req)->first();
            if($req_all->product_id == 0){
                $title_for_noti = $req_all->title;
            } else {
                $pro = Product::where('id', $req_all->product_id)->first();
                $title_for_noti = $pro->title;
            }
            $seller_info = User::select('first_name')->where('id', $req_all->accepted_seller_id)->first();
            //Generate notification
            $not = new Notification();
            $not->user_id = $req_all->buyer_id;
            $not->product_id = $req_all->product_id;
            $not->request_id = $req;
            $not->response_id = 0;
            $not->seller_id = $req_all->accepted_seller_id;
            $not->buyer_id = $req_all->buyer_id;
            $not->notification_text =  $seller_info->first_name.' さんが '.$title_for_noti.' を提供されました';
            $not->opening_status = 'UNOPENED';
            $not->notification_type = 'SERVED';
            $not->save();

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

    public function get_names(Request $request)
    {
        $cat_id = $request->category;
        // $subcat_id = $request->subcategory;
        // $budget_id = $request->budget;
        $cat = Category::select('cat_name')->where('id', $cat_id)->first();
        // $sub_cat = Category::select('cat_name')->where('id', $subcat_id)->first();
        // $budget = Budget::select('budget_range')->where('id', $budget_id)->first();
        $data = [
            'status'  => 200  ,
            'cat' => $cat->cat_name,
            // 'sub_cat' => $sub_cat->cat_name,
            // 'budget' => $budget->budget_range
        ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

    public function details_for_purchase($id=null, $seller=null)
    {
        $request = $id;
        $sell = $seller;
        if($request=='' || $request==null || $sell=='' || $sell==null){
            $e = ApiErrorCodes::where('error_code',10058)->first(); 
            $data = [
                'status'  => 200  ,
                'message' => $e->error_message
            ];
        } else {
            $req = DB::table('request_board AS rb')
                    ->select('rb.title', 'rb.content', 'rb.budget', 'bgt.budget_range', 'rb.product_id')
                    ->leftjoin('budget AS bgt', 'bgt.id', '=', 'rb.budget')
                    ->where('rb.id', $request)
                    ->first();
            $seller = DB::table('users')
                            ->select('first_name')
                            ->where('id', $sell)
                            ->first();
            $response = DB::table('request_response AS rr')
                            ->select('rr.content', 'rr.estimated_deadline', 'rr.estimated_price')
                            ->where('seller_id', $sell)
                            ->where('request_id', $request)
                            ->where('status', 'LAST')
                            ->first();
            if($req->product_id > 0){
                $pro = DB::table('products AS pro')
                                    ->select('pro.title')
                                    ->where('pro.id', $req->product_id)
                                    ->first();
                $product_name = $pro->title;
            } else {
                $product_name = '';
            }
            $data = [
                'status'  => 200,
                'seller' => $seller,
                'request' => $req,
                'response' => $response,
                'product_name' => $product_name
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

}
