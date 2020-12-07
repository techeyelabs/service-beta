@extends('admin.layouts.main')


@section('custom_css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<style>
table.dataTable tbody tr.unread{
    background-color: darkgray;
}
</style>
@endsection



@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    
                    <div class="header">
                        <h2>
                            Direct Message List 
                            <a href="{{route('admin-send-bulk-message')}}" class="btn btn-info pull-right">Send Bulk Message</a>

                            {{-- <a href="{{route('admin-notification-list')}}" class="btn btn-warning pull-right">Notification List</a> --}}
                            
                            
                        </h2>
                        <div class="header-dropdown m-r-0"></div>
                        
                    </div>
                    
                    <div class="body table-responsive">
                       
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th class="text-center"> Pic </th>
                                    <th class="text-center"> Name </th>
                                    <th class="text-center"> Type </th>
                                    <th class="text-center"> Last Message</th>
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
                order: [ [4, 'desc'] ],
                ajax: "{{route('admin-direct-message-list-data')}}",
                columns: [
                    { data: 'profile_pic', name: 'profile_pic' },
                    { data: 'name', name: 'name' },
                    { data: 'type', name: 'type' },
                    { data: 'content', name: 'content' },
                    { data: 'created_at', name: 'created_at' }
                ],
                "createdRow": function( row, data, dataIndex){
                    if( data.status ==  'Unread'){
                        $(row).addClass('unread');
                    }else{
                        $(row).addClass('read');
                    }
                },
            });
        });
    </script>
@endsection
