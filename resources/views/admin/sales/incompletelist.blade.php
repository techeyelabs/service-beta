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
                            <a href="{{ route('admin-sales-list') }}" style="cursor: pointer; width: 70px; padding: 5px; text-decoration: none">Complete</a>&nbsp;
                            <a href="{{ route('admin-sales-list-incompletes') }}" style="cursor: pointer;  text-decoration: none; width: 70px; padding: 5px; border-bottom: 5px solid #3c5998;">Incomplete</a>
                        </div>
                    </div>
                    
                    <div class="body table-responsive">
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
                                    {{-- <th class="text-center"> Transaction Fee </th>
                                    <th class="text-center"> Affiliator Earning </th>
                                    <th class="text-center"> Seller Earning </th> --}}
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
                window.location.href = '{{route("admin-sales-list-incompletes")}}'+'?from='+start.format('MM/DD/YYYY')+'&to='+end.format('MM/DD/YYYY');
                // alert("A new date selection was made: " + start.format('MM/DD/YYYY') + ' to ' + end.format('MM/DD/YYYY'));
            });




            var dataTable = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                //bSort: false,
                order: [ [6, 'desc'] ],
                ajax: "{{route('admin-sales-list-data-incompletes', ['date_range' => $dateRange])}}",
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
                    // {
                    //     data: 'transaction_fee',
                    //     name: 'transaction_fee'
                    // },
                    // {
                    //     data: 'affiliator_earning',
                    //     name: 'affiliator_earning'
                    // },
                    // {
                    //     data: 'seller_earning',
                    //     name: 'seller_earning'
                    // },
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
