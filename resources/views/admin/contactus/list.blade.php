@extends('admin.layouts.main')


@section('custom_css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection



@section('content')

@endsection
@section('custom_js')
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    
                    <div class="header">
                        <h2>Contact List</h2>
                        <div class="header-dropdown m-r-0"></div>
                    </div>
                    
                    <div class="body table-responsive">

                       
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th class="text-center"> Name </th>
                                    <th class="text-center"> content </th>
                                    <th class="text-center"> email </th>
                                    <th class="text-center"> Created at </th>
                                </tr>
                            </thead>
                            <?php
                                foreach($contactlist as $cont) {
                            ?>
                                <tr>
                                    <td class="text-center"> {{$cont->name}} </td>
                                    <td class="text-center"> {!! nl2br($cont->content) !!} </td>
                                    <td class="text-center"> {{$cont->email}} </td>
                                    <td class="text-center"> {{$cont->created_at}} </td>
                                </tr>
                            <?php } ?>
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
