<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\TopSlider;
use App\Models\AdminRight;
use DB;
use DataTables;
use Auth;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights
        ];
        return view('admin.service.list', $data);
    }

    public function listData(Request $request)
    {
        $result = Product::with('price')->with('seller')->with('category')->with('subCategory');
        if(!empty($request->seller_id)){
            $result = $result->where('seller_id', $request->seller_id);
        }
        if(!empty($request->category_id)){
            $result = $result->where('category_id', $request->category_id);
        }
        if(!empty($request->sub_category_id)){
            $result = $result->where('sub_category_id', $request->sub_category_id);
        }
        $result = $result->get();
        return Datatables::of($result)
            ->editColumn('title', function ($result){
                return '<a href="'.route('admin-service-details',['id' => $result->id]).'">'.$result->title.'</a>';
            })   
            ->addColumn('seller', function ($result){
                if(empty($result->seller)) return 'no seller';
                return '<a href="'.route('admin-seller-details', ['id' => $result->seller_id]).'">'.$result->seller->first_name. ' '.$result->seller->last_name.'</a><a href="?seller_id='.$result->seller_id.'"> <i class="fa fa-filter"></i></a>';
            })            
            ->addColumn('price', function ($result){
                return \floor($result->price->price).' 円';
            })
            ->addColumn('category', function ($result){
                return $result->category->cat_name.'<a href="?category_id='.$result->category_id.'"> <i class="fa fa-filter"></i></a>';
            })
            ->addColumn('sub_category', function ($result){
                return $result->subCategory->cat_name.'<a href="?sub_category_id='.$result->sub_category_id.'"> <i class="fa fa-filter"></i></a>';
            })
            ->editColumn('status', function ($result) {
                $output = '';
                if ($result->status == 'ACTIVE'){
                    return '<span class="text-success">'.$result->status.'</span>';
                }elseif($result->status == 'INACTIVE'){
                    return '<span class="text-danger">'.$result->status.'</span>';
                }elseif($result->status == 'SOLD'){
                    return '<span class="text-warning">'.$result->status.'</span>';
                }
                return $output;
            })
            ->addColumn('action', function ($result) {
                $output = '';
                if ($result->status == 'INACTIVE'){
                    $output .= '<a href="'.route('admin-service-change-status', ['id' => $result->id, 'status'=> 'ACTIVE']).'" class="btn btn-sm btn-info">Activate</a> ';
                }elseif($result->status == 'ACTIVE'){
                    $output .= '<a href="'.route('admin-service-change-status', ['id' => $result->id, 'status'=> 'INACTIVE']).'" class="btn btn-sm btn-danger">Deactivate</a> ';
                }
                return $output;
            })
            ->rawColumns(['action', 'status', 'category', 'sub_category', 'seller', 'title'])
            ->make(true);
    }

    public function changeStatus(Request $request)
    {
        $Product = Product::find($request->id);        
        $Product->status = $request->status;
        $Product->save();
        return redirect()->back()->with('success_message', 'action completed successfully');
    }

    public function details(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $result = Product::with('price')->with('seller')->with('category')->with('multipleImages')->with('subCategory')->find($request->id);
        $data = [
            'rights' => $rights,
            'data' => $result
        ];
        return view('admin.service.details', $data);
    }

    public function banner(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $result = TopSlider::where('status', 'ACTIVE')->get();
        $data = [
            'rights' => $rights, 
            'data' => $result
        ];
        return view('admin.service.banner', $data);
    }

    public function banner_save(Request $request)
    {
    //    dd($_FILES);
        $prev_total = TopSlider::count();
        if($prev_total == 15){
            $result = TopSlider::where('status', 'ACTIVE')->get();
        } else {
            $slider = new TopSlider();
            $slider->link = $request->link;
            $slider->image_name = $_FILES['banner_file']['name'];
            $slider->save();
            move_uploaded_file($_FILES['banner_file']['tmp_name'],"assets/topslider/".$_FILES['banner_file']['name']);
            $result = TopSlider::where('status', 'ACTIVE')->get();
        }
        // return view('admin.service.banner', ['data' => $result]);
        return redirect()->back();
    }

    public function deleteBanner(Request $request)
    {
        $banner = TopSlider::find($request->id);        
        $banner->status = 'INACTIVE';
        $banner->save();
        // return redirect()->back()->with('success_message', 'action completed successfully');
        $result = TopSlider::where('status', 'ACTIVE')->get();
        return view('admin.service.banner', ['data' => $result]);
    }
}
