@extends('admin.layouts.main')

@section('custom_css')
    <style>
        
        .item{
            cursor: pointer;
        }
        .submit_button{
            border: 1px solid gray;
            border-radius: 4px;
            padding-left: 10px;
            padding-right: 10px;
        }

        td {
            padding-top: 20px
        }

        .input_field{
            width: 60%; 
            border: 1px solid #d0d0d0; 
            border-radius: 4px
        }
    
    </style>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <!--
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>-->

                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>Edit Admin</h2>
                                <div class="header-dropdown m-r-0"></div>
                            </div>
                            @if($errors->any())
                                <div class="header header-dropdown m-r-0"><h4>{{$errors->first()}}</h4></div>
                            @endif
                            <div class="body table-responsive">
                                <div style="margin-left: 25%">
                                    <form method="post" id="new_admin" action="{{route('admin-edit-action')}}" enctype="multipart/form-data">
                                        <table style="width: 100%">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 30%">Admin Username</td>&nbsp;
                                                    <td style="width: 70%"><input class="input_field" type="text" id="admin_name" name="admin_name" value="{{$admins->name}}" required/></td>
                                                    <input type="hidden" id="admin_id" name="admin_id" value="{{$admins->id}}" required/></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%">Admin Email</td>&nbsp;
                                                    <td style="width: 70%"><input class="input_field" type="text" id="admin_email" name="admin_email" value="{{$admins->email}}" required/></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%">Admin Password</td>&nbsp;
                                                    <td style="width: 70%"><input type="text" id="admin_password" name="admin_password" style="width: 60%; border: 1px solid #d0d0d0; border-radius: 4px"/></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%; vertical-align: top;">Permissions</td>&nbsp;
                                                    <td style="width: 70%">
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="service_list" name="service_list" value="service_list" {{ old('service_list', $admins->rights->service_list) === 1 ? 'checked' : '' }}>
                                                            <label for="service_list"> Service List</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="sales" name="sales" value="sales" {{ old('sales', $admins->rights->total_sale) === 1 ? 'checked' : '' }}>
                                                            <label for="sales"> Total Sales</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="withdraw_request" name="withdraw_request" value="withdraw_request" {{ old('withdraw_request', $admins->rights->withdraw_request) === 1 ? 'checked' : '' }}>
                                                            <label for="withdraw_request"> Withdraw Request</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="buyer" name="buyer" value="buyer" value="buyer" {{ old('buyer', $admins->rights->buyers) === 1 ? 'checked' : '' }}>
                                                            <label for="buyer"> Buyers</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="seller" name="seller" value="seller" {{ old('seller', $admins->rights->sellers) === 1 ? 'checked' : '' }}>
                                                            <label for="seller"> Sellers</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="affiliate" name="affiliate" value="affiliate" {{ old('seller', $admins->rights->affiliates) === 1 ? 'checked' : '' }}>
                                                            <label for="affiliate"> Affiliates</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="lottery" name="lottery" value="lottery" {{ old('seller', $admins->rights->lottery) === 1 ? 'checked' : '' }}>
                                                            <label for="lottery"> Lottery</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="banner" name="banner" value="banner" {{ old('seller', $admins->rights->banners) === 1 ? 'checked' : '' }}>
                                                            <label for="banner"> Banners</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="news" name="news" value="news" {{ old('seller', $admins->rights->news) === 1 ? 'checked' : '' }}>
                                                            <label for="news"> News</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="direct_message" name="direct_message" value="direct_message" {{ old('seller', $admins->rights->direct_message) === 1 ? 'checked' : '' }}>
                                                            <label for="direct_message"> Direct Message</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="contact_us" name="contact_us" value="contact_us" {{ old('seller', $admins->rights->contact_us) === 1 ? 'checked' : '' }}>
                                                            <label for="contact_us"> Contact Us</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="admin_list" name="admin_list" value="admin_list" {{ old('seller', $admins->rights->admin) === 1 ? 'checked' : '' }}>
                                                            <label for="admin_list"> Admin List</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="add_admin" name="add_admin" value="add_admin" {{ old('seller', $admins->rights->add_admin) === 1 ? 'checked' : '' }}>
                                                            <label for="add_admin"> Add Admin</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <button class="submit_button" onclick="return confirm('are you sure you want to update this admin?')">submit</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</section>

@endsection
