<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApiErrorCodes;
use App\Models\PurchaseHistory;
use App\Models\ProductPrice;
use App\Models\User;
use App\Models\AffiliatorPoint;
use App\Models\Product;
use DB;

class SellerDashboardController extends Controller
{
    /**************************
        @Name: Get statistics for dashboard
        @URL: backend/seller-dashboard
        @Method: GET
        @params: Seller ID
    ***************************/
    public function seller_dashboard($id=null)
    {
        $total_sale = PurchaseHistory::where('seller_id',$id)
                    ->groupBy('seller_id')
                    ->sum('price');
        $total_vat = PurchaseHistory::where('seller_id',$id)
                    ->groupBy('seller_id')
                    ->sum('vat_tax');
        $total_transaction_fee = PurchaseHistory::where('seller_id',$id)
                    ->groupBy('seller_id')
                    ->sum('transaction_fee');
        $total_aff_commission = PurchaseHistory::where('seller_id',$id)
                    ->groupBy('seller_id')
                    ->sum('aff_commission');
        $earning = $total_sale - $total_vat - $total_transaction_fee - $total_aff_commission;
        $number = PurchaseHistory::where('seller_id',$id)
                    ->count('*');
        $active_number = Product::where('seller_id',$id)
                    ->where('status', 'ACTIVE')
                    ->count('*');
        $data = [
            'status' => 200,
            'total_sale' => $total_sale,
            'total_aff_commission' => $total_aff_commission,
            'number_of_pros_sold' => $number,
            'active_number' => $active_number,
            'earning' => $earning
        ];
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
                    
        
    }
}
