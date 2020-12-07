<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use App\Models\Category;
 
 
use DB;
 
 


class CategoryController extends Controller
{
    public function createCategory()
    {
    	$data['page_title'] = "New Category";
        $data['categories'] = Category::whereNotNull('cat_name')->get();
    	return view("backend.category.new_category" , $data);
    }


    public function categoryList()
    {
        $data['page_title'] = "Categories";
        $data['categories']  =  DB::table('category as c')
                        ->select('c.id' , 'c.cat_name' ,
                                     'cc.cat_name AS parent_category')
                        ->join('category as cc' , 'c.parent_id' , '=' , 'cc.id')
                        ->paginate(10);
 
        return view('backend.category.list' , $data);      
    	//return view('backend.category.category_list' , $data);    	
    }

    public function createCategoryAction(Request $r)
    {
    	///dd($r->all());
    	 $c = new Category();
    	 $c->parent_id = $r->parent_category;
    	 $c->cat_name = $r->cat_name;
    	 $c->status = 'ACTIVE';
    	 $c->created_at = date('Y-m-d H:i:s');
    	 $c->save();
    	 return redirect('/admin/category-list')->with('success' , 'Category saved successfully!!');
    }


 



	public function categoryListTable()
	{
		 
	}

}
