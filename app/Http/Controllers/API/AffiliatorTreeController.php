<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApiErrorCodes;
use App\Models\User;
use DB;

class AffiliatorTreeController extends Controller
{
	public function __construct()
	{
		$this->html[] = ["Name", "Manager", "ToolTip"];
	}
   
  public function tree_data($id=null, $parent='')
	{
		$this->genearteTree($id, $parent);
		return response()->json($this->html);
	}

	private function genearteTree($id, $parent='')
	{
		$userData = User::with('child')->find($id);
		if($userData){
			$this->html[] = [
				[
					'v' => 'user'.$userData->id,
					'f' => $userData->first_name.' '.$userData->last_name

				], 
				$parent!=''?'user'.$parent:'' ,
				$userData->id
			];
			
			foreach($userData->child as $child){
				$this->genearteTree($child->id, $userData->id);
			}
		}
	}
}
