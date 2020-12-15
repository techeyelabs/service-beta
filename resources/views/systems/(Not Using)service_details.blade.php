@extends('systems.main')

@section('custom_css')
<style>
    .alternates110px {
        margin: 0 auto !important;
        max-width: 1100px !important;
    }
    .hr{
        border-top: 1px solid rgb(204, 204, 204);
        width: 98%;
        margin-bottom: 6px;
        margin-top: 6px;
    }
    .card{
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
    }
    .cart_inner{
    border: 1px solid #c8cbd1;
    border-radius: 4px;
     }
     .rounded-circle{
        border-radius: 50%!important;
     }

</style>
@stop

@section('content')
    <div class="alternates110px">
        <div class="col-md-12 row mt-3">
            <div class="col-md-8 l">
                <div class="title" >

                    <span id="title" style="font-style: inherit;font-size:20px;">
                    {{$service->title}}
                    </span></br>

                    <span id="category" style="font-style: inherit;">
                    <i class="fa fa-tag"></i> &nbsp {{  $service->category->cat_name }}
                    </span>

                </div>
                <div class="row">
                    <div class="col-md-8 stars">
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star"></span>
                        <span class="fa fa-star"></span>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="row">
                            <div class="col-md-8">
                            <button class="btn uBtn" style="float: left; width:100%!important;">Follow</button>
                            </div>
                            <div class="col-md-4">

                        <button class="btn uBtn" style="float: right; width:90% !important; color:black" disabled>3</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="hr mt-3"></hr>
                <div class="s1">
                    <div class="row">
                        <div class="col-md-12">
                            <img src=" {{url('uploads/products/'.$service->thumbnail_image)}}" class="img-responsive" alt height="100%" width="100%">
                        </div>
                    </div>
                    <div> &nbsp; </div>
                    <div class="content-details card" style="border: 0px solid; font-size: 13px;">
                        <p>{{$service->summary}}</p>
                    </div>
                </div>
                <div class="s2">
                    <div class="row">
                        <div class="col-md-12">
                            <img src=" {{url($service->service_image)}}" class="img-responsive" alt height="100%" width="100%">
                        </div>
                    </div>
                    <div> &nbsp; </div>
                    <div class="content-details card" style="border: 0px solid; font-size: 13px;">
                        <p>{{$service->service_details}}</p>
                    </div>
                </div>
                <div class="s3">
                    <div class="row">
                        <div class="col-md-12">
                            <img src=" {{url($service->additional_details_image)}}" class="img-responsive" alt height="100%" width="100%">
                        </div>
                    </div>
                    <div> &nbsp; </div>
                    <div class="content-details card" style="border: 0px solid; font-size: 13px;">
                        <p>{{$service->additional_details}}</p>
                    </div>
                </div>

            </div>
            <div class="col-md-4 r">
                <div class="cart_inner" >
                        <div class="cart_option" style="padding:2px;">
                            <div class="payment_price mt-1" style="text-align:center; vertical-align:inherit;">
                                <b>{{$service->price}} BDT</b><span>(continuous billing)</span>
                            </div>
                        </div>
                    <div class="pb-3 mt-3" style="text-align:center;">
                        <button class="btn uBtn" type="submit">Purchase</button>
                    </div>
                </div>
                <div class="cart_inner mt-3 p-2" style="text-align:center; vertical-align: inherit;">
                    <div class="seller_profile">
                        <strong style="font-size:20px"><b>Seller Profile</b></strong>
                    </div>
                    <?php
                        // $pic = parse_url($service->user->profile_pic);
                        // echo '<pre>';
                        // print_r($service->user);
                        // exit;
                        // if(isset($pic['host'])) $pic = $user->profile_pic;
                        // else if(is_file($pic==null)){
                        //     $pic = Request::root().'/uploads'.'/subsystems_icons/'.'demopp.jpg';
                        // }
                        // else {
                        //     $pic = Request::root().'/uploads/'.''.$user->profile_pic;
                        // }
                    ?>
                    <div class="row mt-3 ">
                        <div class=" col-md-12 text-center">
                            <img src=" {{Request::root()}}/uploads/products/1.jpg" alt="" class="rounded-circle p-2" height="100" width="100" style="margin-left: 0px;object-fit: cover;">

                            <div class="seller_profile  mt-3">
                                 <strong style="font-size:13px;">{{$service->user->first_name}} {{$service->user->last_name}} </br> Contact the seller</strong>
                             </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
@section('custom_js')

@stop
