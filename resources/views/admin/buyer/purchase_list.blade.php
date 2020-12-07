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
                        <h2>Buyer Purchase List</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>
                    
                    <div class="body table-responsive">
                       
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th class="text-center"> Buyer </th>
                                    <th class="text-center"> Seller </th>
                                    <th class="text-center"> Product Title </th>
                                    <th class="text-center"> Product Price</th>
                                    {{-- <th class="text-center"> Current Status </th> --}}
                                    <th class="text-center"> Acceptance Status </th>
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
    <script>
        $(document).ready(function(){
            var dataTable = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth : false,
                //bSort: false,
                order: [ [6, 'desc'] ],
                ajax: "{{route('admin-buyer-purchase-list-data', ['buyer_id' => Request::get('buyer_id')])}}",
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
        });
    </script>
@endsection
