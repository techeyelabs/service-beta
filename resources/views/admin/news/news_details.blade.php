@extends('admin.layouts.main')


@section('custom_css')
@endsection



@section('content')
<section class="content" id="app">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">

                    <div class="header">
                        <h2>News Details</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>

                    <div class="body">

                        @include('admin.layouts.message')

                        <div class="row">
                            
                            
                            <div class="col-md-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>News Details <small class="pull-right">{{$news->created_at}}</small></h2>
                                            <div class="header-dropdown m-r-0"></div>
                                        </div>
                                        <div class="body">
                                            {!!$news->notification_text!!}
                                        </div>
                                    </div>
                            </div>
                           
                        </div>
                        

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('custom_js')
    
@endsection
