<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Notification;
use App\Models\AffiliatorPersonalPage;
use App\Models\UserPointWithdrawal;
use App\Models\PurchaseHistory;
use App\Models\AffiliatorEarning;
use App\Models\AffiliatorApplication;
use App\Models\AdminRight;

use DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class AffiliatorController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        return view('admin.affiliator.list', $data);
    }

    public function listData(Request $request)
    {
        $result = User::where('user_type_id', 3)->with('userPointWithdrawal')->get();
        return Datatables::of($result)
            ->addColumn('total_purchase_amount', function ($result){
                return '<a href="'.route('admin-affiliator-details', ['id' => $result->id, 'active_tab' => 'purchase-list']).'">'.\floor($result->purchase->sum('price')).' 円 </a>';
            })
            ->addColumn('balance', function ($result){
                return '<a href="'.route('admin-affiliator-details', ['id' => $result->id, 'active_tab' => 'balance-list']).'">'.\floor($result->userPointWithdrawal->remaining_amount).' 円 </a>';
            })
            ->addColumn('name', function ($result){
                return '<a href="'.route('admin-affiliator-details', ['id' => $result->id]).'">'.$result->first_name. ' '.$result->last_name.'</a>';
            })
            ->editColumn('profile_pic', function ($result) use($request){
                return '<img width="50" height="50" src="'.$request->root().'/assets/images/users/'.$result->profile_pic.'">';
            })
            ->editColumn('status', function ($result) {
                $output = '';
                $currentMonth = date('m');
                $currentYear = date('Y');
                $total_this_month = PurchaseHistory::where('buyer_id', $result->id)->whereRaw('MONTH(created_at) = ?',[$currentMonth])->whereRaw('YEAR(created_at) = ?',[$currentYear])->sum('price');
                if ($total_this_month >= 980){
                    return '<span class="text-info">'.'ACTIVE'.'</span>';
                }else{
                    return '<span class="text-danger">'.'INACTIVE'.'</span>';
                }
                return $output;
            })
            ->addColumn('action', function ($result) {
                $output = '';
                if ($result->status == 'ACTIVE'){
                    $output .= '<a href="'.route('admin-affiliator-change-status', ['id' => $result->id, 'status'=> 'INACTIVE']).'" class="btn btn-sm btn-info">Deactivate</a> ';
                    $output .= '<a href="'.route('admin-affiliator-change-status', ['id' => $result->id, 'status'=> 'SUSPEND']).'" class="btn btn-sm btn-danger">Suspend</a> ';
                }else{
                    $output .= '<a href="'.route('admin-affiliator-change-status', ['id' => $result->id, 'status'=> 'ACTIVE']).'" class="btn btn-sm btn-info">Activate</a> ';
                }
                return $output;
            })
            ->rawColumns(['action', 'status', 'profile_pic', 'balance', 'name', 'total_purchase_amount'])
            ->make(true);
    }

    public function changeStatus(Request $request)
    {
        $User = User::find($request->id);        
        $User->status = $request->status;
        $User->save();

        $Notification = new Notification();
        $Notification->notification_text = 'Your account is '.$request->status.' now.Contact administrator for further query';
        $Notification->notification_type = 'SYSTEM';
        $Notification->save();

        return redirect()->back()->with('success_message', 'action completed successfully');
    }

    public function purchaseListData(Request $request)
    {
        $result = PurchaseHistory::with('buyer')->with('seller')->with('product');
        if(!empty($request->buyer_id)){
            $result = $result->where('buyer_id', $request->buyer_id);
        }        
        $result = $result->get();
        return Datatables::of($result)
            ->addColumn('buyer', function ($result){
                return '<a href="'.route('admin-buyer-details', ['id' => $result->buyer_id]).'">'.$result->buyer->first_name.' '.$result->buyer->last_name.'</a>';
            })
            ->addColumn('seller', function ($result){
                return '<a href="'.route('admin-seller-details', ['id' => $result->seller_id]).'">'.$result->seller->first_name.' '.$result->seller->last_name.'</a>';
            })
            ->addColumn('title', function ($result){
                if(empty($result->product)) return 'no product';
                return '<a href="'.route('admin-service-details', ['id' => $result->product_id]).'">'.$result->product->title.'</a>';
            })
            ->editColumn('price', function ($result){
                return \floor($result->price).' 円';
            })   
            ->rawColumns(['title', 'seller', 'buyer'])
            ->make(true);
    }



    public function affiliatorDetails(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        $data['userInfo'] = User::find($request->id);
        $data['balance'] = UserPointWithdrawal::where('user_id', $request->id)->first();
        $data['sale'] = AffiliatorEarning::where('child_affiliator_id', $request->id)
                                            ->where('source', 'SALE')
                                            ->whereYear('created_at', Carbon::now()->year)
                                            ->whereMonth('created_at', Carbon::now()->month)
                                            ->sum('earning_amount');
        $data['recruitment'] = AffiliatorEarning::where('child_affiliator_id', $request->id)
                                                ->where('source', 'RECBONUS')
                                                ->whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->month)
                                                ->sum('earning_amount');
        $data['rank'] = AffiliatorEarning::where('child_affiliator_id', $request->id)
                                            ->where('source', 'RANKBONUS')
                                            ->whereYear('created_at', Carbon::now()->year)
                                            ->whereMonth('created_at', Carbon::now()->month)
                                            ->sum('earning_amount');
        $data['lottery'] = AffiliatorEarning::where('child_affiliator_id', $request->id)->where('source', 'LOTTERY')->sum('earning_amount');
        return view('admin.affiliator.affiliator_details', $data);
    }

    public function affiliatedProductListData(Request $request)
    {
        $result = AffiliatorPersonalPage::where('affiliator_id', $request->user_id)->with('product')->with('affiliator');
        $result = $result->get();
        return Datatables::of($result)
            ->addColumn('affiliator', function ($result){
                return $result->affiliator->first_name.' '.$result->affiliator->last_name;
            })
            ->addColumn('price', function ($result){
                return \floor($result->product->price->price).' 円';
            })
            ->addColumn('product', function ($result){
                return '<a href="'.route('admin-service-details', ['id' => $result->product->id]).'">'.$result->product->title.'</a>';
            })
            ->editColumn('line_link', function ($result){
                return '<a target="_blank" href="'.$result->line_link.'">'.$result->line_link.'</a>';
            })
            
            ->rawColumns(['product', 'line_link', 'web_link'])
            ->make(true);
    }


    public function affiliatorEarningList(Request $request)
    {
        // dd($request->all());
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        return view('admin.affiliator.earning_list', $data);
    }

    public function affiliatorEarningListData(Request $request)
    {
        $result = AffiliatorEarning::where('child_affiliator_id', $request->user_id)
                                        ->where('source', $request->source)
                                        ->whereYear('created_at', $request->year)
                                        ->whereMonth('created_at', $request->month)
                                        ->with('product')
                                        ->with('childAffiliator');
        $result = $result->get();
        return Datatables::of($result)
            ->addColumn('affiliator', function ($result){
                return $result->childAffiliator->first_name.' '.$result->childAffiliator->last_name;
            })
            ->addColumn('price', function ($result){
                return \floor($result->price).' 円';
            })
            ->addColumn('earning_amount', function ($result){
                return \floor($result->earning_amount).' 円';
            })
            ->addColumn('percent', function ($result){
                return $result->percent.' %';
            })
            ->addColumn('product', function ($result){
                if(empty($result->product)) return 'no product';
                return '<a href="'.route('admin-service-details', ['id' => $result->product->id]).'">'.$result->product->title.'</a>';
            })
            
            ->rawColumns(['product', 'price', 'earning_amount', 'percent'])
            ->make(true);
    }

    public function affiliatorEarningListMonthly(Request $request)
    {
        DB::enableQueryLog();
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        
        // dd($request->all());
        $result = AffiliatorEarning::select('child_affiliator_id', 'created_at', DB::raw('SUM(earning_amount) AS earn'), DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
                                    ->where('child_affiliator_id', $request->user_id)
                                    ->where('source', $request->source)
                                    ->with('product')
                                    ->with('childAffiliator')
                                    ->groupBy(DB::raw("MONTH(created_at)"))
                                    ->orderBy(DB::raw("YEAR(created_at)"));
        $data['aff_sales'] = $result->get();
        $data['source'] = $request->source;
        return view('admin.affiliator.earning_list_monthly', $data);
    }

    public function affiliatorEarningListDataMonthly(Request $request)
    {
        // dd($request->all());
        $result = AffiliatorEarning::where('child_affiliator_id', $request->user_id)
                                        ->where('source', $request->source)
                                        ->with('product')
                                        ->with('childAffiliator');
        $result = $result->get();


        // echo '<pre>';
        // print_r($result);
        // exit;


        return Datatables::of($result)
            ->addColumn('affiliator', function ($result){
                return $result->childAffiliator->first_name.' '.$result->childAffiliator->last_name;
            })
            ->addColumn('price', function ($result){
                return \floor($result->price).' 円';
            })
            ->addColumn('earning_amount', function ($result){
                return \floor($result->earning_amount).' 円';
            })
            ->addColumn('percent', function ($result){
                return $result->percent.' %';
            })
            ->addColumn('product', function ($result){
                if(empty($result->product)) return 'no product';
                return '<a href="'.route('admin-service-details', ['id' => $result->product->id]).'">'.$result->product->title.'</a>';
            })
            
            ->rawColumns(['product', 'price', 'earning_amount', 'percent'])
            ->make(true);
    }

    public function applications(Request $request)
    {
        // dd($request->all());
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        return view('admin.affiliator.applications', $data);
    }

    public function affiliatorApplicationListData(Request $request)
    {
        // dd($request);
        $result = AffiliatorApplication::select('*')->orderBy('id', 'DESC')->get();
        // dd($result);
        return Datatables::of($result)
            ->addColumn('id', function ($result){
                return $result->id;
            })
            ->addColumn('name', function ($result){
                return $result->name;
            })
            ->addColumn('email', function ($result){
                return $result->email;
            })
            ->addColumn('app_body', function ($result){
                return $result->app_body;
            })
            ->addColumn('created_at', function ($result){
                return $result->created_at;
            })
            ->addColumn('action', function ($result) {
                $output = '';
                if ($result->performed == 'NO'){
                    $output .= '<a href="'.route('admin-affiliator-approve', ['id' => $result->id, 'status'=> 'PERFORMED']).'" class="btn btn-sm btn-info">Approve</a> ';
                    // $output .= '<a href="'.route('admin-affiliator-change-status', ['id' => $result->id, 'status'=> 'SUSPEND']).'" class="btn btn-sm btn-danger">Suspend</a> ';
                }else{
                    // $output .= '<a href="'.route('admin-affiliator-change-status', ['id' => $result->id, 'status'=> 'ACTIVE']).'" class="btn btn-sm btn-info">Activate</a> ';
                    $output .= '';
                }
                return $output;
            })
            
            ->rawColumns(['id', 'name', 'email', 'app_body', 'created_at', 'action'])
            ->make(true);
    }

    public function sendLink(Request $request)
    {
        $id = $request->id;
        $req = AffiliatorApplication::where('id', $id)->first();
        $content = $req->app_body;
        $email = $req->email;
        $name = $req->name;
        $title = '';

        //Contact mail user
        $data = [
            'link' => '',
            'name' => $name,
            'mail' => $email,
            'content' => $content,
            'title' => $title
        ];
        \Mail::send('contactuser', $data, function($message) use ($req){
            $message->to($req->email, 'test')->subject
               ('[share-work] お問い合わせ受付完了のお知らせ');
            $message->from('noreply@share-work.jp','ShareWork');
         });
        //Contact mail ends

        //Contact mail admin
        //  $data = [
        //     'link' => '',
        //     'name' => $request->name,
        //     'mail' => $email,
        //     'content' => $content,
        //     'title' => $title
        // ];
        // \Mail::send('contactadmin', $data, function($message) use ($request){
        //     $message->to('support@share-work.jp', 'test')->subject
        //        ('[share-work]　管理者用】新規問合せの通知');
        //     $message->from('noreply.sharework@gmail.com','ShareWork');
        //  });
        //Contact mail ends
        $data = [
            'status'  => 200
        ];

        $req->performed = 'YES';
        $req->save();
        return redirect()->back()->with('success_message', 'action completed successfully');
    }
}
