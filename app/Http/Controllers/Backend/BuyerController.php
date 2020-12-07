<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
 
 

class BuyerController extends Controller
{

	public function buyerList()
	{
		//$data['page_title'] = "Buyer List";
		//return view('backend.buyers.list.buyer_list' , $data);

		$data['page_title'] = "Buyer List";
		$buyers = User::where('user_type_id',1)->paginate(10);
		$data['buyers'] = $buyers;
		return view('backend.buyers.list' , $data);
	}


	public function buyerListTable()
	{
		 
	}


}