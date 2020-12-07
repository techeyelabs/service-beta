<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\ApiErrorCodes;
use App\Models\ProductPrice;
use App\Models\ProductImage;
use App\Models\PurchaseHistory;
use App\Models\UserPointWithdrawal;
use App\Models\SystemBalance;
use App\Models\AffiliatorEarning;
use App\Models\RequestBoard;
use App\Models\ImmediateHolding;
use App\Models\Notification;
use App\Models\AffiliatorPersonalPage;
use DB;

use GuzzleHttp\Client;


class PurchaseController extends Controller
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

    public function cancelPayment(Request $request)
    {
        return redirect('/payment/'.$request->CustomerId);
    }
    public function retreat(Request $request)
    {
        return redirect('/');
    }
    /**************************
        @Name: KICK for bank payment
        @URL: backend/bankKick
        @Method: GET
    ***************************/
    public function kick_bank(Request $request)
    {
        $result = $request->Result;
        $SiteId = $request->SiteId;
        $buyer_name = $request->name;
        $buyer_mail = $request->mail;
        $note = $request->note;
        $TransactionId = $request->TransactionId;
        $SiteTransactionId = $request->SiteTransactionId;
        $KickType = $request->KickType;
        $bankName = $request->bankName;
        $bankCode = $request->bankCode;
        $branchName = $request->branchName;
        $bankNo = $request->bankNo;
        $acc_name = isset($request->bankAccountName)?$request->bankAccountName: ''; 
        $acc_type = isset($request->bankType)?$request->bankType: ''; 
        $acc_no = isset($request->bankNo)?$request->bankNo: ''; 
        //usual params
        $product_id = isset($request->itemId)?$request->itemId: 0; 
        $buyer_id = isset($request->CustomerId)?$request->CustomerId: '';  
        $service_id = isset($request->service_id)?$request->service_id: '';  
        $paymentMethod = isset($request->paymentMethod)?$request->paymentMethod: '';   
        $payment_method_id = 2;   
        $request_id = isset($request->request_id)?$request->request_id: '';  
        $affiliator_id = isset($request->affiliator_id)?$request->affiliator_id: 0;   
        $price = isset($request->Amount)?$request->Amount: 0;
        $price_not = $request->Amount;
        if($result == 'SALE'){
            if($product_id != 0){
                $seller_whole = Product::select('seller_id')->where('id', $product_id)->first();
                $seller_id = $seller_whole->seller_id;
            } else {
                $seller_whole = RequestBoard::select('accepted_seller_id')->where('id', $request_id)->first();
                $seller_id = $seller_whole->seller_id;
            }
    
                
            //Get all necessary values from product that is being bought starts
            $all = Product::where('id', $product_id)->first();
            if(empty($all)){
                $vat = $price * (20/100);
                $aff_commission = 0;
            } else {
                $seller_id = $all->seller_id;
                $price_id = $all->price_id;
                $aff_commission = $all->affiliator_commission_amount;
                $fee_flag = $all->commission_in_ex_vat;
                $vat = $all->commission_include_vat_amount;
                // Get price amount from price ID
                $price_all = ProductPrice::where('id', $price_id)->first();
                $price = $price_all->price;
                //Get all necessary values from product that is being bought ends
            }
            


            
            //Balance adjustment process starts

            //Amount to be paid by buyer
            $cost = floor($price);
            // Check buyers balance eligibility to pay
            $prev = UserPointWithdrawal::where('user_id', $buyer_id)->first();
            if((($prev->remaining_amount) - $cost) < 0 && $payment_method_id==3){
                //Buyer doesn't have enough balance
                $e = ApiErrorCodes::where('error_code',10061)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
                // return response()->json($request);
            //Eligibility checked


            //Process the purchase
            } else {
                //Buyer point deduction
                if($payment_method_id == 3){
                    UserPointWithdrawal::where('status', 'ACTIVE')
                    ->where('user_id', $buyer_id)
                    ->update(
                        [
                            'remaining_amount' => $prev->remaining_amount - $cost
                        ]
                    );
                }
                $inter = new ImmediateHolding();
                $inter->product_id = $product_id;
                $inter->request_id = $request_id;
                $inter->price = $price;
                $inter->seller_id = $seller_id;
                $inter->buyer_id = $buyer_id;
                $inter->aff_commission = $aff_commission;
                $inter->status = 'PENDING';
                $inter->save();
                //Buyer point deducted

                //Update storage
                $pro_for_stock = Product::where('id', $product_id)->first();
                if($pro_for_stock){
                    Product::where('id', $product_id)
                    ->update(
                        [
                            'no_of_order_at_atime' => $pro_for_stock->no_of_order_at_atime - 1
                        ]
                    );
                }
                //Storage update ends
                

                //Get additional product info
                $additionals = Product::select('id', 'service_id', 'seller_id', 'category_id', 
                                'sub_category_id', 'title', 'expected_delivery_days')
                                ->where('id', $product_id)
                                ->first();

                $Date = date('Y-m-d H:i:s');
                if(!empty($additionals)){
                        //Request management
                    $deadline = date('Y-m-d', strtotime($Date. ' + '.$additionals->expected_delivery_days.' days'));
                    $req = new RequestBoard();
                    $req->buyer_id = $buyer_id;
                    $req->accepted_seller_id = $seller_id;
                    $req->product_id = $product_id;
                    $req->category = $additionals->category_id;
                    $req->sub_category = $additionals->sub_category_id;
                    $req->title = $additionals->title;
                    $req->content = isset($request->content)?$request->content:'empty';
                    $req->proposed_budget = $price;
                    $req->proposed_delivery_time = $deadline;
                    $req->affiliator_id = $affiliator_id;
                    $req->current_status = 'TRADING';
                    $req->trading_status = 'BUYERPAID';
                    $req->acceptance_status = 'ACCEPTED';
                    $req->payment_date = date("Y-m-d");
                    $req->save();
                } else {
                    RequestBoard::where('id', $request_id)->where('buyer_id', $buyer_id)
                    ->update(
                        [
                            'current_status' => 'TRADING',
                            'trading_status' => 'BUYERPAID',
                            'payment_date' => date("Y-m-d")
                        ]
                    );
                }           
                

                $buyer_info = User::select('first_name')->where('id', $buyer_id)->first();
                $service_name_all = Product::select('title')->where('id', $product_id)->first();
                if(empty($service_name_all)){
                    $name = ' ';
                } else {
                    $name = $service_name_all->title;
                }

                $not = new Notification();
                $not->user_id = $seller_id;
                $not->product_id = $product_id;
                $not->request_id = $service_id;
                $not->response_id = 0;
                $not->seller_id = $seller_id;
                $not->buyer_id = $buyer_id;
                $not->notification_text = $buyer_info->first_name.'さんが '.$name.' を購入されました。';
                $not->opening_status = 'UNOPENED';
                $not->notification_type = 'PURCHASE';
                $not->save();


                $data = [
                    'status'  => 200  ,
                    'message' => 'success'
                ];
            } 
        } else if($result == 'PAYWAIT' || $KickType == 'BANK_OK'){
            if($product_id != 0){
                $product_name = Product::select('title', 'price_id')->where('id', $product_id)->first();
                $title = "購入サービス: ".$product_name->title;
            } else {
                $all = RequestBoard::select('title', 'proposed_budget')->where('id', $request_id)->first();
                $title = $all->title;
            }
            if(!empty($all)){
                $noti_price = $all->proposed_budget;
            } else if(!empty($product_name)){
                $price_all = ProductPrice::select('price')->where('id', $product_name->price_id)->first();
                $noti_price = $price_all->price;
            }
            $payment_alert = '指定の口座にお振り込みください。'.'<br/>'.
                                $title.'<br/>'.
                                '振込金額           :'.$noti_price.'<br/>'.
                                '取引ID            :'.$TransactionId.'<br/>'.
                                '銀行名             :'.$bankName.'<br/>'.
                                '支店名             :'.$branchName.'<br/>'.
                                '口座種類           :'.$acc_type.'<br/>'.
                                '口座番号           :'.$acc_no.'<br/>'.
                                '口座名義           :'.$acc_name.'<br/>';
            $not = new Notification();
            $not->user_id = $buyer_id;
            $not->product_id = $product_id;
            $not->request_id = $service_id;
            $not->response_id = 0;
            $not->seller_id = 0;
            $not->buyer_id = $buyer_id;
            $not->notification_text = $payment_alert;
            $not->opening_status = 'UNOPENED';
            $not->notification_type = 'SYSTEM';
            $not->save();
        }
        
        return;
    }
    /**************************
        @Name: store purchase info
        @URL: backend/purchase
        @Method: POST
    ***************************/
    public function store(Request $request)
    {
        if(isset($request->TransactionId)){
            $product_id = isset($request->itemId)?$request->itemId: 0;    
            $credit_card_customer_id = isset($request->CustomerId)?$request->CustomerId: 0;    
            if($product_id > 0){
                $buyer_id = isset($request->CustomerId)?($request->CustomerId - $request->specialValue): '';   
            } else {
                $buyer_id = isset($request->CustomerId)?($request->CustomerId - $request->specialValue): '';  
            }    
            $service_id = isset($request->service_id)?$request->service_id: '';      
            $paymentMethod = isset($request->paymentMethod)?$request->paymentMethod: '';   
            $payment_method_id = 1;  
            $request_id = isset($request->request_id)?$request->request_id: '';      
            $affiliator_id = isset($request->affiliator_id)?$request->affiliator_id: 0;      
            $price = isset($request->Amount)?$request->Amount: 0;
            if($product_id != 0){
                $seller_whole = Product::select('seller_id')->where('id', $product_id)->first();
                $seller_id = $seller_whole->seller_id;
            } else {
                $seller_whole = RequestBoard::select('accepted_seller_id')->where('id', $request_id)->first();
                $seller_id = $seller_whole->seller_id;
            }
            // additionals from gateway
            $CardName = isset($request->CardName)?$request->CardName: '';      
            $dba = isset($request->dba)?$request->dba: '';      
            $CardBrand = isset($request->CardBrand)?$request->CardBrand: '';      
            $paymentMethodName = isset($request->paymentMethodName)?$request->paymentMethodName: '';      
            $Result = isset($request->Result)?$request->Result: '';      
            $TransactionId = isset($request->TransactionId)?$request->TransactionId: '';      
            $paymentType = isset($request->paymentType)?$request->paymentType: '';      
            $saleResult = isset($request->saleResult)?$request->saleResult: '';      
            $CustomerPass = isset($request->CustomerPass)?$request->CustomerPass: '';     
        } else {
            $credit_card_customer_id = isset($request->CustomerId)?$request->CustomerId: 0; 
            $product_id = isset($request->product_id)?$request->product_id: 10000000000000000;      
            $buyer_id = isset($request->buyer_id)?$request->buyer_id: '';
            $service_id = isset($request->service_id)?$request->service_id: $request->request_id;
            $payment_method_id = isset($request->payment_method_id)?$request->payment_method_id: '';
            $seller_id = isset($request->seller_id)?$request->seller_id: 0;
            $affiliator_id = isset($request->aff_id)?$request->aff_id: 0;
            $price = isset($request->estimated_price)?$request->estimated_price: 0;
            $request_id = isset($request->request_id)?$request->request_id: '';
        }
        // Initial data allocation ends
        // return response()->json($service_id);
		
        // Validation starts
        
            // Validation ends
            //Get all necessary values from product that is being bought starts
            $all = Product::where('id', $product_id)->first();
            if(empty($all)){
                $vat = $price * (20/100);
                $aff_commission = 0;
            } else {
                $seller_id = $all->seller_id;
                $price_id = $all->price_id;
                $aff_commission = $all->affiliator_commission_amount;
                $fee_flag = $all->commission_in_ex_vat;
                $vat = $all->commission_include_vat_amount;
                // Get price amount from price ID
                $price_all = ProductPrice::where('id', $price_id)->first();
                $price = $price_all->price;
                //Get all necessary values from product that is being bought ends
            }
           


            
            //Balance adjustment process starts

            //Amount to be paid by buyer
            $cost = floor($price);
            // Check buyers balance eligibility to pay
            $prev = UserPointWithdrawal::where('user_id', $buyer_id)->first();
            // dd($prev);
            // print_r($prev);
            // echo('<pre>');
            // exit;
            if((($prev->remaining_amount) - $cost) < 0 && $payment_method_id==3){
                //Buyer doesn't have enough balance
                $e = ApiErrorCodes::where('error_code',10061)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
                // return response()->json($request);
            //Eligibility checked


            //Process the purchase
            } else {
                //Buyer point deduction
                if($payment_method_id == 3){
                    UserPointWithdrawal::where('status', 'ACTIVE')
                    ->where('user_id', $buyer_id)
                    ->update(
                        [
                            'remaining_amount' => $prev->remaining_amount - $cost
                        ]
                    );
                }
                $inter = new ImmediateHolding();
                $inter->product_id = $product_id;
                $inter->request_id = $product_id;
                $inter->price = $price;
                $inter->seller_id = $seller_id;
                $inter->buyer_id = $buyer_id;
                $inter->aff_commission = $aff_commission;
                $inter->status = 'PENDING';
                $inter->save();
                //Buyer point deducted

                //Update storage
                $pro_for_stock = Product::where('id', $product_id)->first();
                if($pro_for_stock){
                    Product::where('id', $product_id)
                    ->update(
                        [
                            'no_of_order_at_atime' => $pro_for_stock->no_of_order_at_atime - 1
                        ]
                    );
                }
                //Storage update ends
                

                //Get additional product info
                $additionals = Product::select('id', 'service_id', 'seller_id', 'category_id', 
                                'sub_category_id', 'title', 'expected_delivery_days')
                                ->where('id', $product_id)
                                ->first();
            	RequestBoard::where('id', 647)
                    ->update(
                        [
                            'credit_card_customer_id' => 10
                        ]
                    );

                $Date = date('Y-m-d H:i:s');
                if(!empty($additionals)){
                     //Request management
                    $deadline = date('Y-m-d', strtotime($Date. ' + '.$additionals->expected_delivery_days.' days'));
                    $req = new RequestBoard();
                    $req->buyer_id = $buyer_id;
                    $req->accepted_seller_id = $seller_id;
                    $req->product_id = $product_id;
                    $req->category = $additionals->category_id;
                    $req->sub_category = $additionals->sub_category_id;
                    $req->title = $additionals->title;
                    $req->content = isset($request->content)?$request->content:'empty';
                    $req->proposed_budget = $price;
                    $req->proposed_delivery_time = $deadline;
                    $req->affiliator_id = $affiliator_id;
                    $req->current_status = 'TRADING';
                    $req->trading_status = 'BUYERPAID';
                    $req->acceptance_status = 'ACCEPTED';
                    $req->credit_card_customer_id = $credit_card_customer_id;
                    $req->payment_date = date("Y-m-d");
                    $req->save();
                } else {
                    RequestBoard::where('id', $request_id)->where('buyer_id', $buyer_id)
                    ->update(
                        [
                            'current_status' => 'TRADING',
                            'trading_status' => 'BUYERPAID',
                            'credit_card_customer_id' => $credit_card_customer_id,
                            'payment_date' => date("Y-m-d")
                        ]
                    );
                }           
               

                $buyer_info = User::select('first_name')->where('id', $buyer_id)->first();
                $service_name_all = Product::select('title')->where('id', $product_id)->first();
                if(empty($service_name_all)){
                    $name = ' ';
                } else {
                    $name = $service_name_all->title;
                }

                $not = new Notification();
                $not->user_id = $seller_id;
                $not->product_id = $product_id;
                $not->request_id = $service_id;
                $not->response_id = 0;
                $not->seller_id = $seller_id;
                $not->buyer_id = $buyer_id;
                $not->notification_text = $buyer_info->first_name.'さんが '.$name.' を購入されました。';
                $not->opening_status = 'UNOPENED';
                $not->notification_type = 'PURCHASE';
                $not->save();


                $data = [
                    'status'  => 200  ,
                    'message' => 'success'
                ];
            }          
        
        $details = User::where('id', $buyer_id)->first();
        if($payment_method_id == 3){
            return response()->json($data);
        }
        else if($details->user_type_id == 1){
            return redirect('/buyer-transaction');
        } else if($details->user_type_id == 2){
            return redirect('/seller-buyer-transaction');
        } else {
            return redirect('/affiliator-buyer-transaction');
        }
    }
    /**************************
        @Name: show seller purchase history
        @URL: backend/seller-purchase
        @Method: GET
        @Parameter: Seller id
    ***************************/
    public function seller_purchase_history($id=null)
    {
        $seller = $id;
        if($seller){
            $all_purchase = PurchaseHistory::where('seller_id', $seller)->get();
            if(empty($all_purchase)){
                $e = ApiErrorCodes::where('error_code',10041)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            } else {
                $data = [
                    'status'  => 200  ,
                    'payload' => $all_purchase
                ];
            }
        } else {
            $e = ApiErrorCodes::where('error_code',10032)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        }
        return response()->json($data);
    }
     /**************************
        @Name: show buyer purchase history
        @URL: backend/buyer-purchase
        @Method: GET
        @Parameter: buyer id
    ***************************/
    public function buyer_purchase_history($id=null)
    {
        $buyer = $id;
        if($buyer){
            $all_purchase = PurchaseHistory::where('buyer_id', $buyer)->get();
            if(empty($all_purchase)){
                $e = ApiErrorCodes::where('error_code',10041)->first(); 
                $data = [
                    'status'  => $e->error_code  ,
                    'message' => $e->error_message
                ];
            } else {
                $data = [
                    'status'  => 200  ,
                    'payload' => $all_purchase
                ];
            }
        } else {
            $e = ApiErrorCodes::where('error_code',10044)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        }
        return response()->json($data);
    }


    public function execute_response_acceptance(Request $request)
    {
        // return response()->json($request->estimated_price);
        $estimated_price = $request->estimated_price;
        $estimated_deadline = $request->estimated_deadline;
        $buyer = $request->buyer_id;
        $seller = $request->seller_id;
        $request = $request->request_id;
        if($buyer==null || $seller==null || $request==null || $estimated_price==null || $estimated_deadline==null){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        }
        return response()->json($data);
    }

    public function get_my_avs($id)
    {
        $av = UserPointWithdrawal::where('user_id', $id)->first();
        if(empty($av)){
            $data = [
                'status'  => 404  ,
                'points' => 0
            ];
        } else {
            $data = [
                'status'  => 200,
                'points' => $av->remaining_amount
            ];
        }
        return response()->json($data);
    }

    public function aff_eli($p_id, $b_id)
    {
        $all = AffiliatorPersonalPage::where('affiliator_id', $b_id)->where('product_id', $p_id)->first();
        $self_affi_all = Product::select('self_affiliate')->where('id', $p_id)->first();
        $is_self = $self_affi_all->self_affiliate;
        if(!empty($all) && $is_self == 'NO'){
            $flag = 0;
        } else {
            $flag = 1;
        }
        $data = [
            'status'  => 200,
            'can_he_buy' => $flag
        ];
        return response()->json($data);
    }
}
