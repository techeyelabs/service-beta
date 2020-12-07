<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\PurchaseHistory;
use App\Models\RequestBoard;
use App\Models\AdminRight;
use DataTables;
use DB;
use Auth;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        // DB::enableQueryLog();
        $date_1 = date('m/01/Y');
        $date_2 = date('m/t/Y');
        $date_from_first = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $date_1)));
        $date_to_first = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $date_2)));
        $data['sales'] = PurchaseHistory::select([DB::raw('SUM(price) as total_price'), DB::raw('SUM(transaction_fee) as total_transaction_fee'), DB::raw('SUM(aff_commission) as total_aff_commission')])
                                        ->whereBetween('created_at', [$date_from_first, $date_to_first])
                                        ->first();
        $data['dateRange'] = date('m/01/Y').' - '.date('m/t/Y');
        if(!empty($request->from) && !empty($request->to)){
            $date_from = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $request->from)));
            $date_to = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $request->to)));
            $data['dateRange'] = $request->from.' - '.$request->to;
            $data['sales'] = PurchaseHistory::select([DB::raw('SUM(price) as total_price'), DB::raw('SUM(transaction_fee) as total_transaction_fee'), DB::raw('SUM(aff_commission) as total_aff_commission')])
                                            ->whereBetween('created_at', [$date_from, $date_to])
                                            ->first();
        }
        return view('admin.sales.list', $data);
    }
    public function indexincompletes(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        $data['sales'] = PurchaseHistory::select([DB::raw('SUM(price) as total_price'), DB::raw('SUM(transaction_fee) as total_transaction_fee'), DB::raw('SUM(aff_commission) as total_aff_commission')])->first();
        $data['dateRange'] = date('m/01/Y').' - '.date('m/t/Y');
        if(!empty($request->from) && !empty($request->to)){
            $data['dateRange'] = $request->from.' - '.$request->to;
        }
        return view('admin.sales.incompletelist', $data);
    }

    public function listData(Request $request)
    {
        $fromDate = null;
        $toDate = null;
        if(!empty($request->date_range)){
            $dateRange = \explode('-', $request->date_range);
            $fromDate = date('Y-m-d 00:00:01', strtotime($dateRange[0]));
            $toDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
        }
        // $result = RequestBoard::with('product')->with('seller')->with('buyer')->whereIn('current_status', ['TRADING', 'COMPLETE', 'CANCEL']);
        $result = RequestBoard::with('product')->with('seller')->with('buyer')->whereIn('current_status', ['COMPLETE']);
        if(!empty($fromDate)){
            $result = $result->where('created_at', '>=', $fromDate);
        }
        if(!empty($toDate)){
            $result = $result->where('created_at', '<=', $toDate);
        }
        $result = $result->get();

        return Datatables::of($result)
            ->addColumn('seller', function ($result){
                if(empty($result->seller)) return 'no seller';
                return '<a href="'.route('admin-seller-details', ['id' => $result->seller->id]).'">'.$result->seller->first_name.' '.$result->seller->last_name.'</a>';
            })
            ->addColumn('buyer', function ($result){
                if(empty($result->buyer)) return 'no buyer';
                return '<a href="'.route('admin-buyer-details', ['id' => $result->buyer_id]).'">'.$result->buyer->first_name.' '.$result->buyer->last_name.'</a>';
            })
            ->editColumn('proposed_budget', function ($result){
                return \floor($result->proposed_budget).' 円';
            })
            ->editColumn('current_status', function ($result){
                if($result->current_status == 'TRADING'){
                    if($result->trading_status == 'BUYERPAID') return '支払済'; 
                    elseif($result->trading_status == 'SELLERSERVED') return '配送済'; 
                    elseif($result->trading_status == 'BUYERGOT') return '完成'; 
                    // elseif($result->trading_status == 'BEFORETRADING') return ''; 
                    // elseif($result->trading_status == 'AFTERTRADING') return ''; 
                    else return $result->current_status;
                }
                return $result->current_status;
            })
            ->editColumn('transaction_fee', function ($result){
                return $result->proposed_budget*(20/100).' 円';
            })
            ->addColumn('affiliator_earning', function ($result){
                if($result->affiliator_id == 0)
                    return '0'.' 円';
                else
                    return (($result->product->affiliator_commission_amount)/2).' 円';
            })
            ->addColumn('ranking_bonus', function ($result){
                if($result->affiliator_id == 0)
                    return '0'.' 円';
                else
                    return ((($result->product->affiliator_commission_amount)/2)*(.40)).' 円';
            })
            ->addColumn('recruiting_bonus', function ($result){
                if($result->affiliator_id == 0)
                    return '0'.' 円';
                else
                    return ((($result->product->affiliator_commission_amount)/2)*(.50)).' 円';
            })
            ->addColumn('lottery', function ($result){
                if($result->affiliator_id == 0)
                    return '0'.' 円';
                else
                    return ((($result->product->affiliator_commission_amount)/2)*(.10)).' 円';
            })
            ->addColumn('seller_earning', function ($result){
                if($result->affiliator_id == 0)
                    return ($result->proposed_budget - $result->proposed_budget*(20/100)).' 円';
                else
                    return ($result->proposed_budget - $result->product->affiliator_commission_amount - $result->proposed_budget*(20/100)).' 円';
            })
            ->addColumn('system_earning', function ($result){
                return (($result->price-$result->aff_commission-$result->transaction_fee)/2).' 円';
            })
            ->addColumn('order', function ($result){
                if(empty($result->product)) return 'no product';
                return '<a href="'.route('admin-service-details', ['id' => $result->product_id]).'">'.$result->id.'</a>';
            })
            ->addColumn('aff_commission', function ($result){
                if(empty($result->product)) return 'no product';
                return '<a href="'.route('admin-service-details', ['id' => $result->product_id]).'">'.$result->product->title.'</a>';
            })
            ->addColumn('product', function ($result){
                if(empty($result->product)) return 'Request';
                return '<a href="'.route('admin-service-details', ['id' => $result->product_id]).'">'.$result->product->id.'</a>';
            })
            
            ->rawColumns(['order', 'product', 'line_link', 'web_link', 'seller', 'buyer'])
            ->make(true);
    }
    public function listDataIncompletes(Request $request)
    {
        $fromDate = null;
        $toDate = null;
        if(!empty($request->date_range)){
            $dateRange = \explode('-', $request->date_range);
            $fromDate = date('Y-m-d 00:00:01', strtotime($dateRange[0]));
            $toDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
        }
        // $result = RequestBoard::with('product')->with('seller')->with('buyer')->whereIn('current_status', ['TRADING', 'COMPLETE', 'CANCEL']);
        $result = RequestBoard::with('product')->with('seller')->with('buyer')->whereIn('current_status', ['TRADING', 'CANCEL']);
        if(!empty($fromDate)){
            $result = $result->where('created_at', '>=', $fromDate);
        }
        if(!empty($toDate)){
            $result = $result->where('created_at', '<=', $toDate);
        }
        $result = $result->get();
        return Datatables::of($result)
            ->addColumn('seller', function ($result){
                if(empty($result->seller)) return 'no seller';
                return '<a href="'.route('admin-seller-details', ['id' => $result->seller->id]).'">'.$result->seller->first_name.' '.$result->seller->last_name.'</a>';
            })
            ->addColumn('buyer', function ($result){
                if(empty($result->buyer)) return 'no buyer';
                return '<a href="'.route('admin-buyer-details', ['id' => $result->buyer_id]).'">'.$result->buyer->first_name.' '.$result->buyer->last_name.'</a>';
            })
            ->editColumn('proposed_budget', function ($result){
                return \floor($result->proposed_budget).' 円';
            })
            ->editColumn('current_status', function ($result){
                if($result->current_status == 'TRADING'){
                    if($result->trading_status == 'BUYERPAID') return '支払済'; 
                    elseif($result->trading_status == 'SELLERSERVED') return '配送済'; 
                    elseif($result->trading_status == 'BUYERGOT') return '完成'; 
                    // elseif($result->trading_status == 'BEFORETRADING') return ''; 
                    // elseif($result->trading_status == 'AFTERTRADING') return ''; 
                    else return $result->current_status;
                }
                return $result->current_status;
            })
            // ->editColumn('transaction_fee', function ($result){
            //     return $result->transaction_fee.' 円';
            // })
            // ->addColumn('affiliator_earning', function ($result){
            //     return $result->aff_commission.' 円';
            // })
            // ->addColumn('seller_earning', function ($result){
            //     return ($result->price-$result->aff_commission-$result->transaction_fee).' 円';
            // })
            // ->addColumn('system_earning', function ($result){
            //     return (($result->price-$result->aff_commission-$result->transaction_fee)/2).' 円';
            // })
            ->addColumn('order', function ($result){
                if(empty($result->product)) return 'no product';
                return '<a href="'.route('admin-service-details', ['id' => $result->product_id]).'">'.$result->id.'</a>';
            })
            ->addColumn('product', function ($result){
                if(empty($result->product)) return 'Request';
                return '<a href="'.route('admin-service-details', ['id' => $result->product_id]).'">'.$result->product_id.'</a>';
            })
            
            ->rawColumns(['order', 'product', 'line_link', 'web_link', 'seller', 'buyer'])
            ->make(true);
    }
}
