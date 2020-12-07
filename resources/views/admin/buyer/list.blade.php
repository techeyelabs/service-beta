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
                        <h2>Buyer List</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>
                    
                    <div class="body table-responsive">
                       
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th class="text-center"> Pic </th>
                                    <th class="text-center"> Name </th>
                                    <th class="text-center"> Email </th>
                                    <th class="text-center"> Purchase Amount</th>
                                    <th class="text-center"> Status </th>
                                    <th class="text-center"> Created At </th>
                                    {{-- <th class="text-center"> Action </th> --}}
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
                order: [ [5, 'desc'] ],
                ajax: "{{route('admin-buyer-list-data')}}",
                columns: [
                    { data: 'profile_pic', name: 'profile_pic' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'total_purchase_amount', name: 'total_purchase_amount' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' }
                    // { data: 'action', name: 'action' }
                ]
            });
        });
    </script>
@endsection
