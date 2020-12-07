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
                                <h2>New Admin</h2>
                                <div class="header-dropdown m-r-0"></div>
                            </div>
                            @if($errors->any())
                                <div class="header header-dropdown m-r-0"><h4>{{$errors->first()}}</h4></div>
                            @endif
                            <div class="body table-responsive">
                                <div style="margin-left: 25%">
                                    <form method="post" id="new_admin" action="{{route('new-admin-add-action')}}" enctype="multipart/form-data">
                                        <table style="width: 100%">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 30%">Admin Username</td>&nbsp;
                                                    <td style="width: 70%"><input type="text" id="admin_name" name="admin_name" style="width: 60%; border: 1px solid #d0d0d0; border-radius: 4px" required/></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%">Admin Email</td>&nbsp;
                                                    <td style="width: 70%"><input type="text" id="admin_email" name="admin_email" style="width: 60%; border: 1px solid #d0d0d0; border-radius: 4px" required/></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%">Admin Password</td>&nbsp;
                                                    <td style="width: 70%"><input type="text" id="admin_password" name="admin_password" style="width: 60%; border: 1px solid #d0d0d0; border-radius: 4px" required/></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 30%; vertical-align: top;">Permissions</td>&nbsp;
                                                    <td style="width: 70%">
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="service_list" name="service_list" value="service_list">
                                                            <label for="service_list"> Service List</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="sales" name="sales" value="sales">
                                                            <label for="sales"> Total Sales</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="withdraw_request" name="withdraw_request" value="withdraw_request">
                                                            <label for="withdraw_request"> Withdraw Request</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="buyer" name="buyer" value="buyer">
                                                            <label for="buyer"> Buyers</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="seller" name="seller" value="seller">
                                                            <label for="seller"> Sellers</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="affiliate" name="affiliate" value="affiliate">
                                                            <label for="affiliate"> Affiliates</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="lottery" name="lottery" value="lottery">
                                                            <label for="lottery"> Lottery</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="banner" name="banner" value="banner">
                                                            <label for="banner"> Banners</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="news" name="news" value="news">
                                                            <label for="news"> News</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="direct_message" name="direct_message" value="direct_message">
                                                            <label for="direct_message"> Direct Message</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="contact_us" name="contact_us" value="contact_us">
                                                            <label for="contact_us"> Contact Us</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="admin_list" name="admin_list" value="admin_list">
                                                            <label for="admin_list"> Admin List</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <input type="checkbox" id="add_admin" name="add_admin" value="add_admin">
                                                            <label for="add_admin"> Add Admin</label><br>
                                                        </div>
                                                        <div style="padding-top: 10px">
                                                            <button class="submit_button" onclick="return confirm('are you sure you want to make this admin?')">submit</button>
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
