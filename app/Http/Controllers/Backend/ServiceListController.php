<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\PurchaseHistory;
use Auth;


class ServiceListController extends Controller
{
 

    public function list()
    {    
        return view("backend.service_list.list");
    }
}