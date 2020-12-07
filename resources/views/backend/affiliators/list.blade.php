@extends('backend.layouts.main_layout')

@section('extra_css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>{{ $page_title }}</h2>
                        <div class="header-dropdown m-r-0">                           
                        </div>
                    </div>
                    <div class="body table-responsive">
                       
                        
                        <table id="buyer_list" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th> 
                                    <th>Phone</th>
                                    <th>Address</th>                                    
                                    <th>Picture</th>
                                    
                                </tr>
                            </thead>  
                            <tbody>
                                @foreach($affiliators as  $a)
                                <tr>
                                    <td>{{ $a->first_name .' '. $a->last_name }}</td>
                                    <td>{{ $a->email }}</td> 
                                    <td>{{ $a->phone }}</td>
                                    <td>{{ $a->address }}</td>                                    
                                    <td>{{ $a->profile_pic }}</td>
                                </tr>
                                @endforeach

                            </tbody>                                                     
                        </table>
                        <div>
                            {{ $affiliators->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('extra_js')
 
@endsection
