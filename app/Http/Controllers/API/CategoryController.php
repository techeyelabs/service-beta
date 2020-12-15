<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use App\Models\Category;
use Yajra\DataTables\Facades\Datatables;
//use datatables;
 

class CategoryController extends Controller
{
    public function createCategory()
    {
    	$data['categories'] = Category::all();
    	return view("backend.category.new_category" , $data);
    }
    

    public function categoryList()
    {
    	return view('backend.category.list');    	
    }

    public function createCategoryAction(Request $r)
    {    	
    	$this->validate($r , [	        
            'cat_name' => 'required|max:255|unique:category'	        
	    ]);

	    $Category = new Category();
		$Category->parent_id = $r->parent_category;
		$Category->cat_name = $r->cat_name;	    
		$Category->status = 'ACTIVE';
		$Category->created_at = date('Y-m-d H:i:s');	    
	    $Category->save();

	    return redirect()->back()->with('success_message', 'Category successfully added !!');
    }


    public function editCategory($id) 
    {
    	
    	$c = Category::find($id);
    	$data['categories'] = Category::all();
    	$data['c'] = $c;
    	return view("backend.category.edit_category" , $data); 
	}
	


 	public function editCategoryAction(Request $r)
    {    			
		$Category = Category::where('id',$r->id)->count();
			
	    if($Category > 0 ) {
			$this->validate($r , [	        
             'cat_name' => 'required|max:255|unique:category'	        
	    	]);
			$c = Category::where('id',$r->id)->first();
	    	$c->cat_name = $r->cat_name;
		    $c->parent_id = $r->parent_category;
		    $c->updated_at = date('Y-m-d H:i:s');
		    $c->update();
	    }
	    return redirect()->back()->with('success_message', 'Category successfully updated !!');
    }



	public function categoryListTable()
	{
		$category = Category::all();
		return Datatables::of($category)
				->addColumn('parent_category', function($result){
					$c = Category::where('id',$result->parent_id)->first();
					if($c)
						return $c->cat_name ;
					else
						return  "";
				})
				->editColumn('id', function($result){
					return '<a href="'. url('/admin/edit-category/'.$result->id ) .'" class="btn btn-success btn-xs">Edit</a>';
				})
				->rawColumns(['id'])
				->make(true);
	}


}
