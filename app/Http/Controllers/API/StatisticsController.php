<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ApiErrorCodes;
use App\Libraries;
use App\Libraries\Common;
use App\Models\PurchaseHistory;
use DB;

class StatisticsController extends Controller
{
    /**************************
        @Name: Fetch basic profile info
        @URL: get-basic-profile
        @Method: GET
        @Params: seller id
    ***************************/
    public function seller_sales_history($id=null)
    {
        $seller = $id;
        if($seller=='' || $seller==null){
            $e = ApiErrorCodes::where('error_code',10041)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
            $sales = DB::table('purchase_history AS ph')
                        ->select('ph.*', 'pr.title AS product_title')
                        ->leftjoin('products AS pr', 'pr.id', '=', 'ph.product_id')
                        ->where('ph.seller_id', $seller)
                        ->orderBy('ph.id', 'DESC')
                        ->get();
            $total_sales = PurchaseHistory::where('seller_id',$id)
                        ->groupBy('seller_id')
                        ->sum('price');
            $aff_commission = PurchaseHistory::where('seller_id',$id)
                        ->groupBy('seller_id')
                        ->sum('aff_commission');
            $vat = PurchaseHistory::where('seller_id',$id)
                        ->groupBy('seller_id')
                        ->sum('vat_tax');
            $transaction_fee = PurchaseHistory::where('seller_id',$id)
                        ->groupBy('seller_id')
                        ->sum('transaction_fee');
            $data = [
                'status'  => 200  ,
                'payload' => $sales,
                'total_sales' => $total_sales,
                'aff_com' => $aff_commission,
                'vat' => $vat,
                't_fee' => $transaction_fee
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }
}
