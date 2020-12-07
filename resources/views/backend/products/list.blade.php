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
                        <h2><?= $page_title ?></h2>
                        <div class="header-dropdown m-r-0">
                           
                        </div>
                    </div>
                    <div class="body table-responsive">                       
                          
                        <table id="product_list" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Service ID</th>
                                    <th>Title</th>
                                    <th>Seller</th>                                     
                                    <th>Category</th>    
                                    <th>Affilitor Commission</th>                                
                                    <th>Picture</th>                                    
                                </tr>
                            </thead> 
                            <tbody>
                                @foreach($products as $p)
                                <tr>
                                    <td>{{ $p->service_id }}</td>
                                    <td>{{ $p->title }}</td>
                                    <td>{{ $p->first_name.' '.$p->last_name }}</td>
                                    <td>{{ $p->cat_name }}</td>    
                                    <td>{{ $p->point_value }}</td>                               
                                    <td><img src="{{ $p->product_image }}" /></td>  
                                </tr>
                                @endforeach
                            </tbody>                                                      
                        </table>
                         <div>
                            {{ $products->links() }}
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
