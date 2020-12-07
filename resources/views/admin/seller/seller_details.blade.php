@extends('admin.layouts.main')


@section('custom_css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endsection



@section('content')



<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="card">
                    <div class="header">
                        <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                            <li class="nav-item {{(request()->get('active_tab') == 'basic-info' || empty(request()->get('active_tab')))?'active':''}}">
                                <a class="nav-link" id="basic-tab" data-toggle="tab" href="#basic" role="tab"
                                    aria-controls="basic" aria-selected="true">Basic Info</a>
                            </li>
                            <li class="nav-item {{request()->get('active_tab') == 'balance-list'?'active':''}}">
                                <a class="nav-link" id="balance-tab" data-toggle="tab" href="#balance" role="tab"
                                    aria-controls="balance" aria-selected="false">Balance Amount</a>
                            </li>
                            <li class="nav-item {{request()->get('active_tab') == 'purchase-list'?'active':''}}">
                                <a class="nav-link" id="purchase-tab" data-toggle="tab" href="#purchase" role="tab"
                                    aria-controls="purchase" aria-selected="false">Purchase List</a>
                            </li>
                            <li class="nav-item {{request()->get('active_tab') == 'sold-list'?'active':''}}">
                                <a class="nav-link" id="sold-tab" data-toggle="tab" href="#sold" role="tab"
                                    aria-controls="sold" aria-selected="false">Sold Product List</a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade {{(request()->get('active_tab') == 'basic-info' || empty(request()->get('active_tab')))?'active in':''}}" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                    <div class="card">

                                            <div class="header">
                                                <h2>Basic Info</h2>
                                                <div class="header-dropdown m-r-0"></div>
                                            </div>
                        
                                            <div class="body table-responsive">

                        
                                                <table class="table" id="">
                                                    <thead>
                                                        
                                                        
                                                    </thead>
                                                    <tbody>

                                                            <tr>
                                                                <td style="width: 20%">Name:</td>
                                                                <td style="width: 80%">{{$userInfo->first_name.' '.$userInfo->last_name}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Pic:</td>
                                                                <td><img width="250" height="250" style="object-fit: cover" src="{{request()->root().'/assets/images/users/'.$userInfo->profile_pic}}"></td>
                                                            </tr>
                                                            
                                                            {{-- <tr>
                                                                <td>Profile Image:</td>
                                                                <td>
                                                                        <img width="50" height="50" src="{{request()->root().'/assets/images/users/'.$userInfo->profile->profile_image}}">
                                                                </td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td>Photo ID:</td>
                                                                <td>
                                                                        <img width="280" height="auto" src="{{request()->root().'/assets/images/photo_id/'.$userInfo->profile->photo_id}}">
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td>Email:</td>
                                                                <td>{{$userInfo->email}}</td>
                                                            </tr>
    
                                                            <tr>
                                                                <td>Profession:</td>
                                                                <td>{{$userInfo->profile->profession}}</td>
                                                            </tr>
    
    
                                                            <tr>
                                                                <td>Utility:</td>
                                                                <td><img width="280" height="auto"  src="{{request()->root().'/assets/images/utility/'.$userInfo->profile->utility}}"></td>
                                                            </tr>
    
                                                            {{-- <tr>
                                                                <td>Age Group:</td>
                                                                <td>{{!empty($userInfo->profile->ageGroup)?$userInfo->profile->ageGroup->age_group:''}}</td>
                                                            </tr> --}}
    
                                                            <tr>
                                                                <td>Sex:</td>
                                                                <td>{{!empty($userInfo->profile->sexName)?$userInfo->profile->sexName->description:''}}</td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td>Personal Details:</td>
                                                                <td>{{$userInfo->profile->personal_details}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Phone No:</td>
                                                                <td>{{$userInfo->phone}}</td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td>Residential Area:</td>
                                                                <td>{{!empty($userInfo->profile->residentialArea)?$userInfo->profile->residentialArea->area:''}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Address:</td>
                                                                <td>{{$userInfo->profile->address}}</td>
                                                            </tr>
                                                            {{-- <tr>
                                                                <td>Creadit Card:</td>
                                                                <td>{{$userInfo->creadit_card}}</td>
                                                            </tr> --}}

                                                            <tr>
                                                                <td>Bank Name:</td>
                                                                <td>{{$userInfo->bank_name}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Branch Name:</td>
                                                                <td>{{$userInfo->branch_name}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Account No.:</td>
                                                                <td>{{$userInfo->transfer_account}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Account Name:</td>
                                                                <td>{{$userInfo->account_name}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Account Type:</td>
                                                                @if($userInfo->account_type == 'general')
                                                                    <td>普通</td>
                                                                @elseif($userInfo->account_type == 'general')
                                                                    <td>当座</td>
                                                                @else
                                                                    <td>貯蓄</td>
                                                                @endif
                                                            </tr>
                                                            

                                                            {{-- <tr>
                                                                <td colspan="2">
                                                                    <h4>Bank Accounts</h4>
                                                                </td>
                                                            </tr> --}}
                                                            <?php foreach ($userInfo->bankAccounts as $key => $value) {?>
                                                                {{-- <tr>
                                                                    <td>
                                                                        {{$value->bank_acct_name}}
                                                                    </td>
                                                                    <td>
                                                                        <strong>AC No:</strong> {{$value->bank_acct_no}}
                                                                        <br>
                                                                        <strong>Branch:</strong> {{$value->bank_acct_branch	}}
                                                                    </td>
                                                                </tr> --}}
                                                            <?php }?>

                                                            
                                                            {{-- <tr>
                                                                <td colspan="2">
                                                                    <h4>Card Information</h4>
                                                                </td>
                                                            </tr> --}}

                                                            <?php foreach ($userInfo->creditCards as $key => $value) {?>
                                                                {{-- <tr>
                                                                    <td>
                                                                        <?php if($value->card_type == 1) echo 'VISA';
                                                                        elseif($value->card_type == 2) echo 'MASTER';
                                                                        elseif($value->card_type == 3) echo 'AMEX';
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <strong>Card No:</strong>{{$value->credit_card_no}}
                                                                        <br>
                                                                        <strong>CVV:</strong>{{$value->cvv}}
                                                                        <br>
                                                                        <strong>Exp Date:</strong>{{$value->expiry_date}}
                                                                    </td>
                                                                </tr> --}}
                                                            <?php }?>
                                                            

                                                    </tbody>
                                                </table>
                        
                                            </div>
                                        </div>
                                        
                            </div>
                            <div class="tab-pane fade {{request()->get('active_tab') == 'balance-list'?'active in':''}}" id="balance" role="tabpanel" aria-labelledby="balance-tab">
                                    <div class="card">

                                            <div class="header">
                                                <h2>Balance Amount</h2>
                                                <div class="header-dropdown m-r-0"></div>
                                            </div>
                        
                                            <div class="body">

                        
                                                <table class="table" id="">
                                                    <thead>
                                                        
                                                        <tr>
                                                            <td>Total Amount</td>
                                                            <td>{{$balance->total_amount}} 円</td>

                                                            <td>Remaining Amount</td>
                                                            <td>{{$balance->remaining_amount}} 円</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>


                                                
                                                <strong>Withdraw Request List</strong>
                                                <hr>
                            

                                                <table class="table" id="data-table-balance">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center"> Name </th>
                                                            <th class="text-center"> Email </th>
                                                            <th class="text-center"> Amount </th>
                                                            <th class="text-center"> Status </th>
                                                            <th class="text-center"> Created At </th>
                                                            <th class="text-center"> Action </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                        
                                            </div>
                                        </div>
                                        
                            </div>
                            <div class="tab-pane fade {{request()->get('active_tab') == 'purchase-list'?'active in':''}}" id="purchase" role="tabpanel" aria-labelledby="purchase-tab">
                                <div class="card">

                                        <div class="header">
                                            <h2>Purchase List</h2>
                                            <div class="header-dropdown m-r-0"></div>
                                        </div>
                    
                                        <div class="body table-responsive">
                    
                                            <table class="table" id="data-table-purchase">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center"> Buyer </th>
                                                        <th class="text-center"> Seller </th>
                                                        <th class="text-center"> Product Title </th>
                                                        <th class="text-center"> Product Price</th>
                                                        <th class="text-center"> Current Status </th>
                                                        {{-- <th class="text-center"> Acceptance Status </th> --}}
                                                        <th class="text-center"> Created At </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                    
                                        </div>
                                    </div>

                                    
                            </div>
                            <div class="tab-pane fade {{request()->get('active_tab') == 'sold-list'?'active in':''}}" id="sold" role="tabpanel" aria-labelledby="sold-tab">
                                    <div class="card">

                                            <div class="header">
                                                <h2>Sold Product List</h2>
                                                <div class="header-dropdown m-r-0"></div>
                                            </div>
                        
                                            <div class="body table-responsive">
                        
                                                <table class="table" id="data-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center"> Seller </th>
                                                            <th class="text-center"> Buyer </th>
                                                            <th class="text-center"> Product </th>
                                                            <th class="text-center"> Price </th>
                                                            <th class="text-center"> Transaction Fee </th>
                                                            <th class="text-center"> Affiliator Earning </th>
                                                            <th class="text-center"> Seller Earning </th>
                                                            <th class="text-center"> Current Status </th>
                                                            <th class="text-center"> Created At </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                        
                                            </div>
                                        </div>

                                        
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</section>
@endsection
@section('custom_js')
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        var dataTableBalance = $('#data-table-balance').DataTable({
                processing: true,
                serverSide: true,
                autoWidth : false,
                //bSort: false,
                order: [ [4, 'desc'] ],
                ajax: "{{route('admin-withdraw-list-data', ['user_id' => $userInfo->id])}}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'requested_amount', name: 'requested_amount' },
                    { data: 'request_status', name: 'request_status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' }
                ]
            });

            var dataTablePurchase = $('#data-table-purchase').DataTable({
                processing: true,
                serverSide: true,
                autoWidth : false,
                //bSort: false,
                order: [ [5, 'desc'] ],
                ajax: "{{route('admin-seller-purchase-list-data', ['buyer_id' => Request::get('id')])}}",
                columns: [
                    { data: 'buyer', name: 'buyer' },
                    { data: 'seller', name: 'seller' },
                    { data: 'title', name: 'title' },
                    { data: 'price', name: 'price' },
                    { data: 'current_status', name: 'current_status' },
                    // { data: 'acceptance_status', name: 'acceptance_status' },
                    { data: 'created_at', name: 'created_at' }
                ]
            });


        var dataTable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth : false,
            //bSort: false,
            order: [
                [7, 'desc']
            ],
            ajax: "{{route('admin-sold-product-list-data', ['user_id' => $userInfo->id])}}",
            columns: [
                    {
                        data: 'seller',
                        name: 'seller'
                    },
                    {
                        data: 'buyer',
                        name: 'buyer'
                    },
                    {
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'transaction_fee',
                        name: 'transaction_fee'
                    },
                    {
                        data: 'affiliator_earning',
                        name: 'affiliator_earning'
                    },
                    {
                        data: 'seller_earning',
                        name: 'seller_earning'
                    },
                    {
                        data: 'current_status',
                        name: 'current_status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ]
        });
    });

</script>
@endsection
