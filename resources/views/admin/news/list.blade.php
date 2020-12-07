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
                        <h2>
                            News List 
                            <a href="{{route('admin-new-news')}}" class="btn btn-info pull-right">New News</a>
                            {{-- <a href="{{route('admin-direct-message-list')}}" class="btn btn-warning pull-right">Direct Message List</a> --}}
                            
                        </h2>
                        <div class="header-dropdown m-r-0"></div>
                        
                    </div>
                    
                    <div class="body table-responsive">
                       
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th class="text-center"> Text </th>
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
                order: [ [1, 'desc'] ],
                ajax: "{{route('admin-notification-list-data')}}",
                columns: [
                    { data: 'notification_text', name: 'notification_text' },
                    { data: 'created_at', name: 'created_at' }
                ]
            });
        });
    </script>
@endsection
