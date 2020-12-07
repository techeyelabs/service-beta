<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
 
 

class AffiliatorController extends Controller
{

	public function affiliatorList()
	{
		$data['page_title'] = "Affiliator List";
		$data['affiliators'] = User::where('user_type_id',3)->paginate(10);
		return view('backend.affiliators.list' , $data);

		//$data['page_title'] = "Affiliator List";
		//return view('backend.affiliators.affiliator_list' , $data);
	}


	public function affiliatorListTable()
	{
		 
	}


}