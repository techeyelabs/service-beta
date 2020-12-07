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
                        <div class="header-dropdown m-r-0"></div>
                    </div>
                    
                    <div class="body table-responsive">
                        <table id="buyer_list" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Service ID</th>
                                    <th>Title</th> 
                                    <th>Aff Commission</th>
                                    <th>TRX Fee</th>                                    
                                    <th>Seller Earning</th>
                                </tr>
                            </thead>  
                            <tbody>
                                @foreach($stat as  $s)
                                <tr>
                                    <td>{{ $s->service_id }}</td>
                                    <td>{{ $s->title }}</td> 
                                    <td>{{ $s->aff_commission }}</td>
                                    <td>{{ $s->transaction_fee }}</td>                                   
                                    <td>{{ $s->seller_earning }}</td>
                                </tr>
                                @endforeach

                            </tbody>                                                     
                        </table>
                        <div>
                            {{ $stat->links() }}
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
