<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\AffiliateReward;
use Illuminate\Http\Request;

use Auth;
use Mail;
Use Alert;
use View;
use DB;
use Image;
use DateTime;
// use Input;

class ServiceProductController extends Controller
{
    public function loadForm()
    {
        $data['categories'] = Category::get();
        $data['affiliate_reward'] = AffiliateReward::get();
    	return view("systems.new_project" , $data);
    }

    public function storeProduct(Request $request)
    {
        $Product = new Product();
        $Product->title = $request->title;

        $name = '';
        if ($request->hasFile('thumbnail_image')) {
            $extension = $request->thumbnail_image->extension();
            $name = time().rand(1000,9999).'.'.$extension;
            $fullPath = 'uploads/products/'.$name;
            $fullPathOriginal = 'uploads/products/'.$name;
            $img = Image::make($request->thumbnail_image);
            $img->save($fullPath);


         }
        $purposeImageOriginal = '';
        if ($request->hasFile('service_image')) {
            $extension = $request->service_image->extension();
            $name2 = Auth::user()->id.time().rand(1000,9999).'.'.$extension;
            $fullPath = 'uploads/products/'.$name2;
            $purposeImageOriginal = 'uploads/products/'.$name2;
            $img = Image::make($request->service_image);
            $img->save($fullPath);

        }
        $detailImageOriginal = '';
        if ($request->hasFile('additional_details_image')) {
            $extension = $request->additional_details_image->extension();
            $name3 = Auth::user()->id.time().rand(1000,9999).'.'.$extension;
            $fullPath = 'uploads/products/'.$name3;
            $detailImageOriginal = 'uploads/products/'.$name3;
            $img = Image::make($request->additional_details_image);
            $img->save($fullPath);
        }

        $Product->thumbnail_image = $name;
         //dd($name);
        $Product->service_image = $purposeImageOriginal;
        $Product->additional_details_image = $detailImageOriginal;
        $Product->user_id = Auth::user()->id;
        $Product->category_id = $request->category;
        $Product->summary = $request->summary;
        $Product->price = $request->price;
        $Product->additional_details = $request->additional_details;
        $Product->service_details = $request->service_details;
        $Product->status = 1;
        $Product->affiliator_commission = $request->reward;
        $Product->self_purchasable =$request-> has('self_purchasable') ? true : false;
        $Product->save();
        // dd($Product);

        return redirect()->route('get-add-service');
    }




}
