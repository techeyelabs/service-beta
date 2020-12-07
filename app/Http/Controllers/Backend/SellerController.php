<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
 
 

class SellerController extends Controller
{
	public function sellerList()
	{
		$data['page_title'] = "Seller List";
		$data['sellers'] = User::where('user_type_id',2)->paginate(10);
		return view('backend.sellers.list' , $data);

		//$data['page_title'] = "Seller List";
		//return view('backend.sellers.seller_list' , $data);
	}

	public function sellerListTable()
	{
		  
	}


}