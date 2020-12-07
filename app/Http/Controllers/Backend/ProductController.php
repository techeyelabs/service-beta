<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
 
 

class ProductController extends Controller
{

	public function productList()
	{
		$data['page_title'] = "Product List";
		$data['products'] = Product::join('category' , 'category.id' , '=' , 'products.category_id')
		->join('users' , 'users.id' , '=' , 'products.seller_id')
		->join('affiliator_points' , 'products.affiliator_commission' , '=' , 'affiliator_points.id')
		->paginate(10);
		return view('backend.products.list' , $data);

		//$data['page_title'] = "Product List";
		//return view('backend.products.product_list' , $data);
	}

	public function productListTable()
	{
		 
	}


}