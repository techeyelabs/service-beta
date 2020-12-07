@extends('admin.layouts.main')

@section('custom_css')
    <style>
        
        .item{
            cursor: pointer;
        }
        .submit_button{
            border: 1px solid gray;
            border-radius: 4px;
            padding-left: 10px;
            padding-right: 10px;
        }

        td {
            padding-top: 20px
        }

        .the_tick{
            height: 25px;
            width: 25px
        }
    
    </style>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <!--
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>-->

                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>All Admin</h2>
                                <div class="header-dropdown m-r-0"></div>
                            </div>
                            @if($errors->any())
                                <div class="header header-dropdown m-r-0"><h4>{{$errors->first()}}</h4></div>
                            @endif
                            <div class="body table-responsive">
                                <div style="margin-left: 1%">
                                    <table style="width: 100%">
                                        <tbody>
                                            <tr>
                                                <th>name</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                            @foreach($admins as $a)
                                                <tr>
                                                    <td style="width: 35%">{{$a->name}}</td>
                                                    <td style="width: 45%">{{$a->email}}</td>
                                                    <td>
                                                        @if($a->id != 1)
                                                            <a href="{{route('edit-admin', ['id' => $a->id])}}" class="btn btn-sm btn-info">edit</a>
                                                            <a href="{{route('delete-admin', ['id' => $a->id])}}" class="btn btn-sm btn-info" style="background-color: red !important" onclick="return confirm('Are you sure you want to delete this admin?')">delete</a>
                                                        @endif
                                                    </td>
                                                   
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</section>

@endsection
