<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApiErrorCodes;
use App\Models\User;
use App\Models\UserPointWithdrawal;
use App\Models\AffiliatorPersonalPage;
use DB;

class AffiliatorQueueController extends Controller
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
    /**************************
        @Name: Get product list to add into affiliator queue
        @URL: backend/add-my-queue
        @Method: GET
        @params: Product ID
    ***************************/
    public function get_list_to_aff(Request $request)
    {
        $layer = $request->layer;
        $leave = ($layer-1)*10;
        $aff_id = $request->aff_id;
        $aff_all = User::where('id', $aff_id)->first();
        $affpost = $aff_all->first_name;
        $category = $request->category_id;
        $seller = $request->seller_name;
        $commission_level = $request->commission_level;
        $recurr_type = $request->autoPayment;
        if($category=='' && $category==null && $seller=='' && $seller==null && $commission_level=='' && $commission_level==null && $recurr_type == '' && $recurr_type == null){
            $e = ApiErrorCodes::where('error_code',10042)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ]; 
        } else if($affpost=='' || $affpost==null){
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ]; 
        } else {
            $aff_name = str_replace(' ', '-', $affpost);
            $aff = strtolower($aff_name);
            $ip = \Config::get('app.aff_pro_url');
            $random_string = $this->randomPrefix(5);
            $products = DB::table('products')
                        ->select('products.*', 'us.first_name', 'cat.cat_name', 'pp.price', DB::raw('DATE(products.created_at) AS day'), 
                            DB::raw('CONCAT("'.$ip.'", "aff-product-detail-from-link/" , products.id , "/" , "'.$aff_id.'" , "-" , "'.$random_string.'") AS line_link'),
                            DB::raw('CONCAT("<a href=", "'.$ip.'", "aff-product-detail-from-link/" , products.id , "/" , "'.$aff_id.'" , "-" , "'.$random_string.'" , ">
                            あなたがここに欲しいものは何でも書いてください</a>") AS web_link'))
                        ->leftjoin('users as us', 'products.seller_id', '=', 'us.id')
                        ->leftjoin('category as cat', 'products.category_id', '=', 'cat.id')
                        ->leftjoin('product_prices as pp', 'products.price_id', '=', 'pp.id')
                        ->where('products.affiliator_commission', '>', 0)
                        ->when($request->category_id, function($query) use ($request){
                            return $query->where('products.category_id', $request->category_id);
                        })
                        ->when($request->seller_name, function($query) use ($request){
                            return $query->where('us.first_name', 'LIKE', '%'.$request->seller_name.'%');
                        })
                        ->when($request->commission_level, function($query) use ($request){
                            return $query->where('products.affiliator_commission', '>=', DB::raw($request->commission_level));
                        })
                        ->when($request->autoPayment != '', function($query) use ($request){
                            return $query->where('products.renewal_type', '=', DB::raw($request->autoPayment));
                        })
                        ->skip($leave)->take(10)
                        ->orderBy('products.id', 'DESC')
                        ->get();
            $total = DB::table('products')
                        ->leftjoin('users as us', 'products.seller_id', '=', 'us.id')
                        ->where('products.affiliator_commission', '>', 0)
                        ->when($request->category_id, function($query) use ($request){
                            return $query->where('products.category_id', $request->category_id);
                        })
                        ->when($request->seller_name, function($query) use ($request){
                            return $query->where('us.first_name', 'LIKE', '%'.$request->seller_name.'%');
                        })
                        ->when($request->commission_level, function($query) use ($request){
                            return $query->where('products.affiliator_commission', '>=', DB::raw($request->commission_level));
                        })
                        ->when($request->autoPayment != '', function($query) use ($request){
                            return $query->where('products.renewal_type', '=', DB::raw($request->autoPayment));
                        })
                        ->count();
            $data = [
                'status'  => 200,
                'payload' => $products,
                'total' => $total
            ];

        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

     /**************************
        @Name: Get all products available to affiliate
        @URL: backend/aff-list-for-queue-all
        @Method: GET
        @params: Affiliator ID, Affiliator name
    ***************************/
    public function get_list_to_aff_all($id=null, $name=null, $layer=null)
    {
        $aff_id = isset($id)?$id:'';
        $affpost = isset($name)?$name: '';
        $leave = ($layer-1)*10;
        if($aff_id=='' || $affpost==''){
            $e = ApiErrorCodes::where('error_code',10042)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ]; 
        } else {
            // $ip = \Config::get('app.aff_pro_url');
            $aff_name = $aff_id;
            $ip = 'https://share-work.jp/';
            // $aff_name = str_replace(' ', '-', $affpost);
            $aff = strtolower($aff_name);
            $random_string = $this->randomPrefix(5);
            $products = DB::table('products')
                        ->select('products.*', 'us.first_name', 'cat.cat_name', 'pp.price', DB::raw('DATE(products.created_at) AS day'), 
                            DB::raw('CONCAT("'.$ip.'", "aff-product-detail-from-link/" , products.id , "/" , "'.$aff_id.'" , "-" , "'.$random_string.'") AS line_link'),
                            DB::raw('CONCAT("<a href=", "'.$ip.'", "aff-product-detail-from-link/" , products.id , "/" , "'.$aff_id.'" , "-" , "'.$random_string.'" , ">
                            あなたがここに欲しいものは何でも書いてください</a>") AS web_link'))
                        ->skip($leave)->take(10)
                        ->leftjoin('users as us', 'products.seller_id', '=', 'us.id')
                        ->leftjoin('category as cat', 'products.category_id', '=', 'cat.id')
                        ->leftjoin('product_prices as pp', 'products.price_id', '=', 'pp.id')
                        ->where('products.status', 'ACTIVE')
                        ->where('products.affiliator_commission', '!=', 0)
                        ->orderBy('products.id', 'DESC')
                        ->get();
            $total = DB::table('products')->where('status', 'ACTIVE')->where('affiliator_commission', '!=', 0)->count();
            $data = [
                'status'  => 200,
                'payload' => $products,
                'total' => $total
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

     /**************************
        @Name: Get affiliators personal queue with link
        @URL: backend/get-aff-personal-queue
        @Method: GET
        @params: Affiliator ID
    ***************************/
    public function get_my_queue($id=null, $layer=null)
    {
        $affiliator = $id;
        $leave = ($layer-1)*10;
        if($affiliator=='' || $affiliator==null){
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                'status'  => $e->error_code,
                'message' => $e->error_message
            ]; 
        } else {
            $queue = DB::table('affiliator_personal_page AS app')
                            ->select('app.*', DB::raw("SUBSTRING(p.title, 1, 40) AS title"), 'p.affiliator_commission_amount', 'p.price_id', 'pp.price', 'p.status', 'p.renewal_type')
                            ->skip($leave)->take(10)
                            ->leftjoin('products as p', 'p.id', '=', 'app.product_id')
                            ->leftjoin('product_prices as pp', 'pp.id', '=', 'p.price_id')
                            ->where('app.affiliator_id', $affiliator)
                            ->orderBy('app.id', 'desc')
                            ->get();
            $total = DB::table('affiliator_personal_page')->where('affiliator_id', $affiliator)->count();
            $data = [
                'status'  => 200,
                'payload' => $queue,
                'total' => $total
            ];                                
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }
}
