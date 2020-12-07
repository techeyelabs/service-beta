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
                       
                        <div>
                        <?php 
                        if (Session::get('success')) 
                        {
                            echo "<div class=\"alert alert-success alert-dismissible\" role=\"alert\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
                               " . Session::get('success') . "
                            </div>";
                        }
                        if (Session::get('error')) 
                        {
                            echo "<div class=\"alert alert-danger alert-dismissible\" role=\"alert\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
                               " . Session::get('error') . "
                            </div>";
                        }  

                        ?>
                        </div>  
                        <table id="categories" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tag</th> 
                                    <th>Create Date</th>
                                    <th><i class="material-icons">settings</i></th>
                                </tr>
                            </thead>                                                       
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_js')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
$(function() {    
    console.log('working ..');
    var dataTable = $('#categories').DataTable({
        processing: true,
        serverSide: true,
        //bSort: false,
        //order: [ [4, 'desc'] ],
        ajax: "{{ url('/admin/category-list-data') }}",
        columns: [
            { data: 'cat_name', name: 'cat_name' },
            { data: 'parent_category', name: 'parent_category' },
            { data: 'created_at', name: 'created_at' },
             { data: 'id', name: 'id' }
        ]
    });
});
</script>
@endsection
