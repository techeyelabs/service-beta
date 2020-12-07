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
                        <h2>Withdraw Requests</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>
                    
                    <div class="body table-responsive">
                       
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th class="text-center"> Name </th>
                                    <th class="text-center"> Email </th>
                                    <th class="text-center"> Amount </th>
                                    <th class="text-center"> Amount to be paid </th>
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
        </div>
    </div>
</section>
@endsection
@section('custom_js')
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            var dataTable = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                //bSort: false,
                order: [ [4, 'desc'] ],
                ajax: "{{route('admin-withdraw-list-data')}}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'requested_amount', name: 'requested_amount' },
                    { data: 'receivable_amount', name: 'receivable_amount' },
                    { data: 'request_status', name: 'request_status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' }
                ]
            });
        });
    </script>
@endsection
