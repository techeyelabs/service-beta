
@extends('backend.layouts.main_layout')

@section('content')
<section class="content">
    <div class="container-fluid"> 
        <!-- Input -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2><?= $page_title ?></h2>
                        <div class="header-dropdown m-r-0">
                            <a class="btn btn-primary" href="{{ url('/admin/category-list') }}">Categories</a> 
                        </div>
                    </div>

                    <div class="body">
             
                       
                        
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

                        <form  action="{{ url('/admin/create-category') }}" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12">
                                     
                                    <div class="form-group form-float">
                                        <label for="tag_name">Category Name</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="cat_name"  data-validation="required length" data-validation-length="max100">
                                            <label class="form-label">Category Name</label>
                                        </div>
                                        <span class="form-helper text-danger"></span>
                                    </div>


                                    <div class="form-group form-float">
                                        <label for="tag_name">Parent Category</label>
                                        <div class="form-line">
                                            <select class="form-control" name="parent_category"  >
                                                <option value="">----Select Parent Category----</option>
                                                @foreach($categories as $c)
                                                <option value="{{ $c->id }}">{{  $c->cat_name }}</option> 
                                                @endforeach
                                            </select>
                                            <!--<label class="form-label">Parent Category</label>-->
                                        </div>
                                        <span class="form-helper text-danger"></span>
                                    </div>



                                    <div class="form-group form-float">                                 
                                        <button type="submit" class="btn btn-primary waves-effect">Save</button> 
                                    </div>
                                    
                                </div> 
                            </div> 
                        </form>
               
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
 @endsection