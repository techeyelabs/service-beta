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
use App\Models\AffiliatorEarning;
use DB;

class AffiliatorSalesController extends Controller
{
    /**************************
        @Name: Get all children from my tree
        @URL: get-my-decendants
        @Method: GET
        @Params: Root parent ID
    ***************************/
    public function get_all_decendants($id=null)
    {
        $affiliator = $id;
        if($affiliator=='' || $affiliator==null)
        {
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                'status'  => $e->error_code  ,
                'message' => $e->error_message
            ];
        } else {
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
                                FROM 
                                    (
                                        SELECT *
                                        from users AS us 
                                        where us.user_type_id = 3 
                                        ORDER BY us.parent_id, us.id
                                    ) users_sorted, 
                                (SELECT @pv := ".$affiliator.") initialisation 
                                WHERE find_in_set(parent_id, @pv) AND length(@pv := concat(@pv, ',', id))
                                "
                            );
            for( $i=0; $i<count($decendants); $i++){
                if($decendants[$i]->commission == null) {
                    unset($decendants[$i]);
                } else {
                    $all = DB::table('affiliator_earnings')
                                ->where('child_affiliator_id', $affiliator)
                                ->where('root_selling_aff', $decendants[$i]->id)
                                ->sum('earning_amount');
                    $decendants[$i]->total = $all;
                }
            }
            // dd($decendants[14]);
            $data = [
                'status'  => 200,
                'payload' => array_values($decendants)
            ];
        }
        return response($data)
                ->header('Content-type','application/json')
                ->header('charset', 'utf-8');
    }

     /**************************
        @Name: Get all my sales
        @URL: get-my-sales
        @Method: GET
        @Params: Affiliator ID
    ***************************/
    public function get_my_sales($id=null, $logged_user=null, $layer=null)
    {
        $me = $logged_user;
        $affiliator = $id;
        $let = 10;
        if($layer==null){
            $leave =0;
        } else{
            $leave = ($layer-1)*$let;
        }
        if($affiliator=='' || $affiliator==null){
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        } else {
            // $soldwithcoms = DB::table('affiliator_personal_page AS app')
            //                 ->select('app.product_id', 'app.created_at', 'pro.service_id', 'pro.title', 'pro.affiliator_commission_amount', 'cat.cat_name', 'pr.price')
            //                 ->skip($leave)->take(10)
            //                 ->leftjoin('products as pro', 'pro.id', '=', 'app.product_id')
            //                 ->leftjoin('category as cat', 'cat.id', '=', 'pro.category_id')
            //                 ->leftjoin('product_prices as pr', 'pr.id', '=', 'pro.price_id')
            //                 ->where('app.status', 'SOLDWITHCOMMISSION')
            //                 ->where('app.affiliator_id', $affiliator)
            //                 ->orderBy('app.id', 'DESC')
            //                 ->get();
            $soldwithcoms = DB::table('affiliator_earnings AS ae')
                                ->select('ae.created_at', 'pro.title', 'us.first_name', 'ae.earning_amount', 'ae.product_id')
                                ->leftjoin('products AS pro', 'pro.id', '=', 'ae.product_id')
                                ->leftjoin('users AS us', 'us.id', '=', 'ae.root_selling_aff')
                                ->where('child_affiliator_id', $me)
                                ->where('root_selling_aff', $affiliator)
                                ->get();
            $total = DB::table('affiliator_personal_page AS app')->where('app.affiliator_id', $affiliator)->count();
            $data = [
                'status'  => 200 ,
                'payload' => $soldwithcoms,
                'total' => $total
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function get_filtered_sale(Request $request)
    {
        $searchkey = $request->searchkey;
        $affiliator = $request->aff;
        if($affiliator=='' || $affiliator==null){
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        } else {
            // $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
            // $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
            // $searchkey = str_replace($search, $replace, $searchkey);
            $sub = AffiliatorPersonalPage::where('affiliator_id', $affiliator)->where('status', 'SOLDWITHCOMMISSION');
            $soldwithcoms = DB::table(DB::raw("({$sub->toSql()}) as app"))
                            ->mergeBindings($sub->getQuery())
                            ->select('app.product_id', 'app.created_at', 'pro.service_id', 'pro.title', 'pro.affiliator_commission_amount', 'cat.cat_name', 'pr.price')
                            ->leftjoin('products as pro', 'pro.id', '=', 'app.product_id')
                            ->leftjoin('category as cat', 'cat.id', '=', 'pro.category_id')
                            ->leftjoin('product_prices as pr', 'pr.id', '=', 'pro.price_id')
                            ->where('pro.title', 'LIKE', '%'.$searchkey.'%')
                            ->orWhere('pro.service_id', 'LIKE', '%'.$searchkey.'%')
                            ->orWhere('cat.cat_name', 'LIKE', '%'.$searchkey.'%')
                            ->get();
                            // ->when(is_numeric($request->searchkey), function($query) use ($request){
                            //     return $query->orWhere('pr.price', '>=', DB::raw($request->searchkey));
                            // })
                            // ->when(is_numeric($request->searchkey), function($query) use ($request){
                            //     return $query->orWhere('pro.affiliator_commission_amount', '>=', DB::raw($request->searchkey));
                            // })
                            
            $data = [
                'status'  => 200 ,
                'payload' => $soldwithcoms
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }


    public function get_my_commissions_list($aff_id=null, $layer=null)
    {
        $aff = $aff_id;
        $leave = ($layer-1)*10;
        if($aff=='' || $aff==null){
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        } else {
            $com = DB::table('affiliator_earnings AS ae')
                    ->select('ae.*', 'us.first_name AS child', 'us_p.first_name AS parent', 'pro.title', 'root.first_name AS root_aff')
                    // ->where('parent_affiliator_id', $aff)
                    // ->orWhere('child_affiliator_id', $aff)
                    ->Where('child_affiliator_id', $aff)
                    ->skip($leave)->take(10)
                    ->leftjoin('users AS us', 'us.id', '=', 'ae.child_affiliator_id')
                    ->leftjoin('users AS us_p', 'us_p.id', '=', 'ae.parent_affiliator_id')
                    ->leftjoin('users AS root', 'root.id', '=', 'ae.root_selling_aff')
                    ->leftjoin('products AS pro', 'pro.id', '=', 'ae.product_id')
                    ->orderBy('ae.id', 'DESC')
                    ->get();
            $total = DB::table('affiliator_earnings')->where('child_affiliator_id', $aff)->count();
            $data = [
                'status'  => 200,
                'payload' => $com,
                'total' => $total
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }


    public function get_my_commissions($aff_id=null)
    {
        $aff = $aff_id;
        if($aff=='' || $aff==null){
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        } else {
            $com = AffiliatorEarning::where('child_affiliator_id', $aff)->orWhere('child_affiliator_id', $aff)->sum('earning_amount');
            $data = [
                'status'  => 200,
                'total' => $com 
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }

    public function total_affiliating($aff_id=null)
    {
        $aff = $aff_id;
        if($aff=='' || $aff==null){
            $e = ApiErrorCodes::where('error_code',10055)->first(); 
            $data = [
                         'status'  => $e->error_code ,
                         'message' => $e->error_message 
                     ];
        } else {
            $total = DB::table('affiliator_personal_page')
                        ->where('affiliator_id', $aff)
                        ->count();
            $data = [
                'status'  => 200,
                'total' => $total
            ];
        }
        return response($data)
            ->header('Content-type','application/json')
            ->header('charset', 'utf-8');
    }
}
