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
                        <h2>Affiliator Earning List</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>
                    
                    <div class="body table-responsive">
                       
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th class="text-center"> Name </th>
                                    <th class="text-center"> Product </th>
                                    <th class="text-center"> Price</th>
                                    <th class="text-center"> Earning Amount</th>
                                    <th class="text-center"> Percentage </th>
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
    {{-- {{json_encode(['source' => request()->source, 'user_id' => request()->user_id])}} --}}
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
                order: [ [5, 'desc'] ],
                ajax: {
                    url: "{{route('admin-affiliator-earning-list-data')}}",
                    data: {!!json_encode(['source' => request()->source, 'user_id' => request()->user_id, 'month' => request()->month, 'year' => request()->year])!!}
                },
                columns: [
                    { data: 'affiliator', name: 'affiliator' },
                    { data: 'product', name: 'product' },
                    { data: 'price', name: 'price' },
                    { data: 'earning_amount', name: 'earning_amount' },
                    { data: 'percent', name: 'percent' },
                    { data: 'created_at', name: 'created_at' }
                ]
            });
        });
    </script>
@endsection
