<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiErrorCodes;
use App\Models\RequestBoard;
use App\Models\Budget;
use App\Models\RequestResponse;
use App\Models\Cookies;
use DB;

class PurchaseTransactionController extends Controller
{
     /**************************
        @Name: Get buyers estimation page data
        @URL: backend/get-my-estimation-all
        @Method: GET
        @Params: Buyer ID
    ***************************/
    public function get_during_estimations_buyer($id=null)
    {
        $buyer = $id;
        if($buyer=='' || $buyer==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        }  else {
            // $estimations = DB::select(
            //                     DB::raw('SELECT 
            //                     rb.id,
            //                     rb.buyer_id,
            //                     rb.accepted_seller_id,
            //                     rb.product_id,
            //                     rb.title,
            //                     rb.application_date,
            //                     rb.budget,
            //                     rb.current_status,
            //                     bgt.budget_range
            //                     FROM request_board AS rb 
            //                     LEFT OUTER JOIN 
            //                         budget AS bgt
            //                         ON rb.budget = bgt.id
            //                     WHERE
            //                     rb.buyer_id = '.$buyer.'
            //                     AND
            //                     rb.current_status = "UNDERESTIMATION"
            //                     AND
            //                     (
            //                     rb.accepted_seller_id > 0
            //                     OR
            //                     EXISTS
            //                         ( 
            //                             SELECT 
            //                                 * 
            //                             FROM request_response 
            //                                 WHERE 
            //                                     request_response.request_id = rb.id 
            //                         ) 
            //                         )
            //                         ORDER BY
            //                         rb.id DESC')
            //                 );
            $d = date('Y-m-d h:i:s');
            $estimations = DB::select(
                                DB::raw('SELECT 
                                rb.id,
                                rb.buyer_id,
                                rb.accepted_seller_id,
                                rb.product_id,
                                rb.title,
                                rb.application_date,
                                rb.budget,
                                rb.current_status,
                                bgt.budget_range
                                FROM request_board AS rb 
                                LEFT OUTER JOIN 
                                    budget AS bgt
                                    ON rb.budget = bgt.id
                                WHERE
                                rb.buyer_id = '.$buyer.'
                                AND
                                rb.current_status = "UNDERESTIMATION"
                                AND
                                rb.acceptance_status = "ACCEPTED" 
                                AND
                                rb.application_date >= CURDATE()
                                    ORDER BY
                                    rb.application_date ASC')
                            );
                            $data = [
                                'status'  => 200,
                                'payload' => $estimations
                            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
    /**************************
        @Name: Get buyers estimation page data
        @URL: backend/get-my-estimation-all
        @Method: GET
        @Params: Buyer ID
    ***************************/
    public function get_during_trading_buyer($id=null)
    {
        $buyer = $id;
        if($buyer=='' || $buyer==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        }  else {
            $tradings = DB::table('request_board AS rb')
                            ->select('rb.id', 'rb.accepted_seller_id', 'rb.acceptance_status', 'rb.payment_date',
                                    'rb.product_id', 'rb.title', 'rb.budget', 'rb.proposed_budget', 'rb.proposed_delivery_time',
                                    'rb.application_date', 'rb.current_status', 'bgt.budget_range', 'pro.service_id')
                            ->leftjoin('budget AS bgt', 'bgt.id', '=', 'rb.budget')
                            ->leftjoin('products AS pro', 'pro.id', '=', 'rb.product_id')
                            ->where('rb.buyer_id', $buyer)
                            ->where('rb.current_status', 'TRADING')
                            ->orderBy('rb.id', 'DESC')
                            ->get();
                            $data = [
                                'status'  => 200,
                                'payload' => $tradings
                            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
    /**************************
        @Name: Get completed purchase list
        @URL: backend/get-my-completed-all
        @Method: GET
        @Params: Buyer ID
    ***************************/
    public function get_completed_buyer($id=null)
    {
        $buyer = $id;
        if($buyer=='' || $buyer==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        }  else {
            $tradings = DB::table('request_board AS rb')
                            ->select('rb.id', 'rb.accepted_seller_id', 'rb.proposed_budget', 'rb.proposed_delivery_time', 'rb.completion_date',
                                    'rb.product_id', 'rb.title', 'rb.budget', 
                                    'rb.application_date', 'rb.current_status', 'bgt.budget_range', 'pro.service_id')
                            ->leftjoin('budget AS bgt', 'bgt.id', '=', 'rb.budget')
                            ->leftjoin('products AS pro', 'pro.id', '=', 'rb.product_id')
                            ->where('rb.buyer_id', $buyer)
                            ->where('rb.current_status', 'COMPLETE')
                            ->orWhere('rb.current_status', 'CANCEL')
                            ->orderBy('rb.completion_date', 'DESC')
                            ->get();
                            $data = [
                                'status'  => 200,
                                'payload' => $tradings
                            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
     /**************************
        @Name: Get seller estimation page data
        @URL: backend/get-my-estimation-seller
        @Method: GET
        @Params: Seller ID
    ***************************/
    public function get_during_estimations_seller($id)
    {
        $seller = $id;
        if($seller=='' || $seller==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        }  else {
            $estimations = DB::select(
                                DB::raw('SELECT 
                                rb.id,
                                rb.accepted_seller_id,
                                rb.product_id,
                                rb.title,
                                rb.application_date,
                                rb.budget,
                                rb.current_status,
                                bgt.budget_range
                                FROM request_board AS rb 
                                LEFT OUTER JOIN 
                                    budget AS bgt
                                    ON rb.budget = bgt.id
                                WHERE
                                rb.accepted_seller_id = '.$seller.'
                                AND
                                rb.current_status = "UNDERESTIMATION"
                                AND
                                EXISTS
                                    ( 
                                        SELECT 
                                            1 
                                        FROM request_response 
                                            WHERE 
                                                request_response.seller_id = '.$seller.' 
                                    ) OR
                                EXISTS
                                    ( 
                                        SELECT 
                                            1 
                                        FROM request_board 
                                            WHERE request_board.accepted_seller_id = '.$seller.'
                                    )
                                    ORDER BY
                                    rb.id DESC')
                            );
                            $data = [
                                'status'  => 200,
                                'payload' => $estimations
                            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
     /**************************
        @Name: Get seller during trading data
        @URL: backend/get-my-tradings-seller
        @Method: GET
        @Params: Seller ID
    ***************************/
    public function get_during_trading_seller($id)
    {
        $seller = $id;
        if($seller=='' || $seller==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        }  else {
            $tradings = DB::table('request_board AS rb')
                            ->select('rb.id', 'rb.buyer_id', 'rb.accepted_seller_id', 'rb.acceptance_status', 'us.first_name AS buyer', 'rb.payment_date',
                                        'rb.product_id', 'rb.proposed_budget', 'rb.title', 'pro.service_id',
                                        'rb.proposed_delivery_time', 'rb.current_status')
                            ->leftjoin('products AS pro', 'pro.id', '=', 'rb.product_id')
                            ->leftjoin('users AS us', 'us.id', '=', 'rb.buyer_id')
                            ->where('rb.accepted_seller_id', $seller)
                            ->where('rb.current_status', 'TRADING')
                            ->orderBy('rb.id', 'DESC')
                            ->get();
            $data = [
                'status'  => 200 ,
                'payload' => $tradings
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
    /**************************
        @Name: Get seller complete/cancel data
        @URL: backend/get-my-completed-seller
        @Method: GET
        @Params: Seller ID
    ***************************/
    public function get_completed_seller($id)
    {
        $seller = $id;
        if($seller=='' || $seller==null){
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        }  else {
            $tradings = DB::table('request_board AS rb')
                            ->select('rb.id', 'rb.buyer_id', 'rb.accepted_seller_id', 'rb.proposed_budget', 
                                        'rb.proposed_delivery_time', 'rb.completion_date',
                                        'rb.product_id', 'rb.proposed_budget', 'rb.title',
                                        'rb.proposed_delivery_time', 'rb.current_status')
                            ->where('rb.accepted_seller_id', $seller)
                            ->Where('rb.current_status', 'COMPLETE')
                            ->orderBy('rb.completion_date', 'DESC')
                            ->get();
            $data = [
                'status'  => 200 ,
                'payload' => $tradings
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
    /**************************
        @Name: Set or update cookies
        @URL: backend/cookie-alternate
        @Method: POST
    ***************************/
    public function get_cookies(Request $request)
    {
        $buyer = $request->buyer_id;
        $affiliator = $request->affiliator_id;
        $product = $request->product_id;
        if($buyer=='' || $buyer==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];
        } else if($affiliator=='' || $affiliator==null){
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];
        } else if($product=='' || $product==null){
            $e = ApiErrorCodes::where('error_code',10052)->first(); 
            $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];
        } else {
            $prev = Cookies::where('buyer_id', $buyer)->where('product_id', $product)->first();
            if($prev=='' || $prev==null){
                $cook = new Cookies();
                $cook->buyer_id = $buyer;
                $cook->affiliator_id = $affiliator;
                $cook->product_id = $product;
                $cook->save();
                $e = ApiErrorCodes::where('error_code',10043)->first(); 
                $data = [
                            'status'  => 200 ,
                            'message' => $e->error_message 
                        ];
            } else {
                Cookies::where('buyer_id', $buyer)
                    ->where('product_id', $product)
                    ->update(
                        [
                            'affiliator_id' => $affiliator
                        ]
                    );
                $e = ApiErrorCodes::where('error_code',10051)->first(); 
                $data = [
                            'status'  => 200 ,
                            'message' => $e->error_message 
                        ];
            }
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
    /**************************
        @Name: Retrieve previous browsing records
        @URL: backend/cookie-history
        @Method: GET
        @Params: Usre ID, Product ID
    ***************************/
    public function get_cookie_aff($user_id=null, $product_id=null)
    {
       $buyer = $user_id;
       $product = $product_id;
       if($buyer=='' || $buyer==null){
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];
       } else if($product=='' || $product==null){
            $e = ApiErrorCodes::where('error_code',10052)->first(); 
            $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];
       } else {
            $history = Cookies::where('buyer_id', $buyer)->where('product_id', $product)->first();
            if($history=='' || $history==null){
                $data = [
                    'status'  => 200,
                    'affiliator' => 0 
                ];
            } else {
                $data = [
                    'status'  => 200,
                    'affiliator' => $history->affiliator_id
                ];
            }
       }
       return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
    /**************************
        @Name: Confirm if a link exists
        @URL: backend/valid-link
        @Method: POST
    ***************************/
    public function link_validation(Request $request)
    {
        $hit_link = $request->link;
        if($hit_link=='' || $hit_link==null){
            $e = ApiErrorCodes::where('error_code',10054)->first(); 
            $data = [
                        'status'  => $e->error_code ,
                        'message' => $e->error_message 
                    ];
        } else {
            $exist = DB::table('affiliator_personal_page')
                        ->where('line_link', 'LIKE', '%'.$hit_link)
                        ->first();
            if($exist=='' || $exist==null){
                $e = ApiErrorCodes::where('error_code',10077)->first(); 
                $data = [
                            'status'  => $e->error_code ,
                            'message' => $e->error_message 
                        ];
            } else {
                $data = [
                    'status'  => 200,
                    'payload' => $hit_link
                ];
            }
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
}
