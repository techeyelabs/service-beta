<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;



class HomeController extends Controller
{
	public function test()
	{
		$categoris = Category::where('parent_id',NULL)->get();
		$data = [];
		foreach ($categoris as $c) {
			$cat_id = $c->id ;
			$subCategories =  Category::select('cat_name')->where('parent_id',$cat_id)->get();
			$data[] = [
				'id' => $c->id ,
				'cat_name' => $c->cat_name ,
				'subcategories' => $subCategories
			];
		}
		return response()->json($data);
	}

    public function index(Request $r)
    {
		return \File::get(public_path() . '/front_end/index.html');
    }
}


