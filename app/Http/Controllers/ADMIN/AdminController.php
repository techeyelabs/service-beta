<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminRight;
use App\Models\AdminUser;

use Auth;


class AdminController extends Controller
{
    public function allAdmin()
    {
        $admins = AdminUser::with('rights')->where('status', 1)->get();
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights,
            'admins' => $admins
        ];
        return view('admin.all_admin' ,$data);
    }

    public function edit($id)
    {
        $admin = $id;
        $admins = AdminUser::with('rights')->where('id', $admin)->first();
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $data = [
            'rights' => $rights,
            'admins' => $admins
        ];
        return view('admin.edit_admin' ,$data);
    }
    public function delete($id)
    {
        $admin = AdminUser::find($id);
        $admin->update([
            'status' => 0
        ]);
        return redirect()->back();
    }

    
    public function newAdmin(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $rights = AdminRight::where('admin_id', $id)->first();
        $admins = AdminUser::get();
        $data = [
            'rights' => $rights,
            'admins' => $admins
        ];
    	return view('admin.new_admin' ,$data);
    }

    public function editAction(Request $request)
    {
        $device = AdminUser::find($request->admin_id);
        $device->update([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'role_id' => 1,
            'status' => 1
        ]);
        if($request->admin_password != '' && $request->admin_password != null){
            $device->update([
                'password' => bcrypt($request->admin_password)
            ]);
        }

        $rightData = [
            'admin_id' => $request->admin_id,
            'service_list' => ($request->service_list == 'service_list')?1:0,
            'total_sale' => isset($request->sales)?1:0,
            'withdraw_request' => isset($request->withdraw_request)?1:0,
            'buyers' => isset($request->buyer)?1:0,
            'sellers' => isset($request->seller)?1:0,
            'affiliates' => isset($request->affiliate)?1:0,
            'lottery' => isset($request->lottery)?1:0,
            'banners' => isset($request->banner)?1:0,
            'news' => isset($request->news)?1:0,
            'direct_message' => isset($request->direct_message)?1:0,
            'contact_us' => isset($request->contact_us)?1:0,
            'admin' => isset($request->admin_list)?1:0,
            'add_admin' => isset($request->add_admin)?1:0
        ];
        $updateOrder = AdminRight::where('admin_id', $request->admin_id)->update($rightData);

        return redirect()->back();
    }

    public function newAdminAction(Request $request)
    {
        $userData = [
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => bcrypt($request->admin_password),
            'role_id' => 1,
            'status' => 1
        ];
        $newUser = AdminUser::create($userData);

        $rightData = [
            'admin_id' => $newUser->id,
            'service_list' => ($request->service_list == 'service_list')?1:0,
            'total_sale' => isset($request->sales)?1:0,
            'withdraw_request' => isset($request->withdraw_request)?1:0,
            'buyers' => isset($request->buyer)?1:0,
            'sellers' => isset($request->seller)?1:0,
            'affiliates' => isset($request->affiliate)?1:0,
            'lottery' => isset($request->lottery)?1:0,
            'banners' => isset($request->banner)?1:0,
            'news' => isset($request->news)?1:0,
            'direct_message' => isset($request->direct_message)?1:0,
            'contact_us' => isset($request->contact_us)?1:0,
            'admin' => isset($request->admin_list)?1:0,
            'add_admin' => isset($request->add_admin)?1:0
        ];
        $newUser = AdminRight::create($rightData);

        return redirect()->back();
    }
}
