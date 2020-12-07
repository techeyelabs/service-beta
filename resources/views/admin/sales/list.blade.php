@extends('admin.layouts.main')


@section('custom_css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection



@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    
                    <div class="header">
                        <h2>Sales List</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>
                    <div class="" style="">
                        <div style="padding: 20px">
                            <a href="{{ route('admin-sales-list') }}" style="cursor: pointer; border-bottom: 5px solid #3c5998; width: 70px; padding: 5px; text-decoration: none">Complete</a>&nbsp;
                            <a href="{{ route('admin-sales-list-incompletes') }}" style="cursor: pointer;  text-decoration: none">Incomplete</a>
                        </div>
                    </div>
                    
                    <div class="body table-responsive">


                        <table class="table" id="">
                            <thead>
                                <tr>
                                    <td colspan="2">Total Price</td>
                                    <td colspan="2">{{\floor($sales->total_price)}} 円</td>
                                    <td colspan="2">Total Transaction Fee</td>
                                    <td colspan="2">{{\floor($sales->total_transaction_fee)}} 円</td>
                                    
                                </tr>
                                <tr>
                                    <td>Affiliate Commision</td>
                                    <td>{{\floor($sales->total_aff_commission*50/100)}} 円</td>
                                    <td>Reqruiting Bonus</td>
                                    <td>{{\floor($sales->total_aff_commission*20/100)}} 円</td>
                                    <td>Ranking Bonus</td>
                                    <td>{{\floor($sales->total_aff_commission*25/100)}} 円</td>
                                    <td>Lottery Bonus</td>
                                    <td>{{\floor($sales->total_aff_commission*5/100)}} 円</td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <strong>
                            List
                            <input type="text" class="pull-right dates" name="dates" value="{{$dateRange}}">
                        </strong>
                        <hr>
                       
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th class="text-center"> Seller </th>
                                    <th class="text-center"> Buyer </th>
                                    <th class="text-center"> Order ID </th>
                                    <th class="text-center"> Product ID </th>
                                    <th class="text-center"> Proposed Budget </th>
                                    <th class="text-center"> Transaction Fee </th>
                                    <th class="text-center"> Affiliator Earning </th>
                                    <th class="text-center"> Ranking bonus </th>
                                    <th class="text-center"> Recruiting bonus </th>
                                    <th class="text-center"> lottery </th>
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
</section>
@endsection
@section('custom_js')
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function(){



            $('input[name="dates"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                window.location.href = '{{route("admin-sales-list")}}'+'?from='+start.format('MM/DD/YYYY')+'&to='+end.format('MM/DD/YYYY');
                // alert("A new date selection was made: " + start.format('MM/DD/YYYY') + ' to ' + end.format('MM/DD/YYYY'));
            });




            var dataTable = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                //bSort: false,
                order: [ [12, 'desc'] ],
                ajax: "{{route('admin-sales-list-data', ['date_range' => $dateRange])}}",
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
                        data: 'order',
                        name: 'order'
                    },
                    {
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'proposed_budget',
                        name: 'proposed_budget'
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
                        data: 'ranking_bonus',
                        name: 'ranking_bonus'
                    },
                    {
                        data: 'recruiting_bonus',
                        name: 'recruiting_bonus'
                    },
                    {
                        data: 'lottery',
                        name: 'lottery'
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
