<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
 
use DB;

class PurchaseHistory extends Controller
{
    
	public function purchase_hist()
	{
		$data['page_title'] = "Purchase History";
		$data['purchase_list'] = User::join('purchase_history' , 'purchase_history.seller_id' , '=' , 'users.id')
		->join('products' , 'products.id' , '=' , 'purchase_history.product_id')
		->join('category' , 'products.category_id' , '=' , 'category.id')
		->join('affiliator_points' , 'affiliator_points.id' , '=' , 'products.affiliator_commission')
		->paginate(10);
		return view('backend.purchase-history.list' ,$data);


		//$data['page_title'] = "Purchase History";
		//return view('backend.purchase-history.purchase_list' ,$data);
	}

	public function purchase_history_datatable()
	{
		 
	}

}

 