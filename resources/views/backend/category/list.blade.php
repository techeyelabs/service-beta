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
                            <a class="btn btn-primary" href="{{ url('/admin/create-category') }}">New Category</a> 
                        </div>
                    </div>
                    <div class="body table-responsive">
                       
                        
                        <table id="categories" class="table table-striped">
                            <thead>
                                <tr>
                                     
                                    <th>Category</th> 
                                    <th>Parent Category</th>
                                    <th><i class="material-icons">settings</i></th>
                                </tr>
                            </thead> 
                            <tbody>
                                @foreach($categories as $c)
                                <tr>
                                    
                                    <td>{{ $c->cat_name }}</td> 
                                    <td>{{ $c->parent_category }}</td>                                    
                                    <td><a href="/admin/category-edit/{{ $c->id }}" class="btn btn-primary btn-xs">Edit</a></td>
                                </tr>
                                @endforeach
                            </tbody>                                                      
                        </table>
                         <div>
                            {{ $categories->links() }}
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
