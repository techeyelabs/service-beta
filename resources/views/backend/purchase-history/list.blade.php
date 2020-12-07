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
                       
                        
                        <table id="purchase_list" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Service ID</th>
                                    <th>Buyer</th> 
                                    <th>Seller</th>
                                    <th>Category</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Service Fee</th>
                                     
                                </tr>
                            </thead>  

                            <tbody>
                                @foreach($purchase_list as $p)
                                <tr>
                                    <td>{{ $p->service_id }}</td>
                                    <td>{{ '' }}</td> 
                                    <td>{{ '' }}</td>
                                    <td>{{ $p->cat_name }}</td>
                                    <td>{{ $p->title }}</td>
                                    <td>{{ $p->price }}</td>
                                    <td>{{ 'VT:'.$p->vat_tax .' Trx Fee:'.$p->transaction_fee }}</td>
                                </tr>
                                @endforeach
                            </tbody>  

                        </table>
                         <div>
                            {{ $purchase_list->links() }}
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
