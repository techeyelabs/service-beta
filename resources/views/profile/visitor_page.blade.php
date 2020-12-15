@extends('systems.main')

@section('custom_css')

<style type="text/css">

</style>

@stop
@section('content')

{{-- <!-- @include('user.layouts.tab') --> --}}
<section class="auth_form_area pt-5">
    <div class="col-md-12 alternates" style="min-height: 500px">
        <div class="col-sm-12 bg-white area_auth">
            <div class="col-md-12 col-sm-12 part_1 text-left">
                <!-- Get Banner pic -->
                <?php
                        $banner = parse_url($user->banner);
                        if(isset($banner['host'])){
                             $banner = $user->banner;
                            }
                        else if(is_file($banner==null)){
                            $banner = Request::root().'/uploads'.'/subsystems_icons/'.'democp.jpg';
                        }
                        else {
                            $banner = Request::root().'/uploads/'.''.$user->banner;
                        }
                    ?>
                <div class="">
                    <img class="banner_image" style="width: 100%; height: 300px; object-fit: contain ;"
                        src="{{$banner}}">
                </div>
                <!-- Get Profile Pic -->
                <?php
                        $pic = parse_url($user->pic);
                        if(isset($pic['host'])) $pic = $user->pic;
                        else if(is_file($pic==null)){
                            $pic = Request::root().'/uploads'.'/subsystems_icons/'.'demopp.jpg';
                        }
                        else {
                            $pic = Request::root().'/uploads/'.''.$user->pic;
                        }
                    ?>
                <!-- Profile Pic Div -->
                <div class="">
                    <img class="small_profile_image"
                        style="border-radius: 50%; width: 175px; margin-top: -60px; margin-left: 22%; border: 5px solid white; object-fit:cover;"
                        src="{{$pic}}" alt="...">

                </div>
                <!-- Name,points and social network div -->
                <div class="col-md-7" style="display: none; margin-left:35%; ">
                    <div>
                        <b>
                            <i>{{ $user->first_name }} {{$user->last_name}}</i>
                        </b>
                    </div>
                    <div>
                        <i class="fa fa-trophy"></i>&nbsp;{{ $user->point }}
                    </div>
                    <div>
                        @if($user->facebook_id =='')
                        <a>
                            <img class="" style="border-radius: 50%; width: 35px"
                                src="/uploads/profile/facebook_gray.png" alt="...">
                        </a>
                        @else
                        <a>
                            <img class="" style="border-radius: 50%; width: 35px"
                                src="/uploads/profile/facebook_blue.png" alt="...">
                        </a>
                        @endif
                        @if($user->google_id =='')
                        <a>
                            <img class="" style="border-radius: 50%; width: 35px" src="/uploads/profile/google_gray.png"
                                alt="...">
                        </a>
                        @else
                        <a>
                            <img class="" style="border-radius: 50%; width: 35px" src="/uploads/profile/google_blue.png"
                                alt="...">
                        </a>
                        @endif
                        @if($user->twitter_id =='')
                        <a>
                            <img class="" style="border-radius: 50%; width: 35px"
                                src="/uploads/profile/twitter_gray.png" alt="...">
                        </a>
                        @else
                        <a>
                            <img class="" style="border-radius: 50%; width: 35px"
                                src="/uploads/profile/twitter_blue.png" alt="...">
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- My Profile Section   -->
        <div class="col-md-12 alternates" style="max-width: 500px !important; min-height: 500px">
            <div class="text-center pb-3">
                <span style="font-size: 20px">
                    <b>My Profile</b>
                </span>
            </div>
            <hr />
            <!-- My Profile Form  -->
            <div class="text-left pb-4">
                <p style="margin-bottom: 0px !important">First Name</p>
                <input id="fname" name="fname" type="text" class="input_boxes" disabled value="{{$user->first_name}}" />
            </div>
            <div class="text-left pb-4">
                <p style="margin-bottom: 0px !important">Last Name</p>
                <input id="lname" name="lname" type="text" class="input_boxes" disabled value="{{$user->last_name}}" />
            </div>
            <div class="text-left pb-4">
                <p style="margin-bottom: 0px !important">E mail</p>
                <input id="email" name="email" type="text" class="input_boxes" disabled value="{{$user->email}}" />
            </div>
            <div class="text-left pb-4" id="phoneHide">
                <p style="margin-bottom: 0px !important">Phone</p>
                <input id="phone" name="phone" type="text" class="input_boxes" disabled
                    value="{{$user->profile->phone_no}}" />
            </div>
            <div class="text-left pb-4" id="addressHide">
                <p style="margin-bottom: 0px !important">Address</p>
                <textarea id="address" name="address" type="text" class="input_textarea"
                    disabled>{{$user->profile->address}}</textarea>
            </div>
        </div>
        <!--  Introduction div -->
        <div style="height: 50px"></div>
        <div class="col-md-12 alternates" style="max-width: 500px !important;" id="introHide">
            <div class="text-center pb-3">
                <span style="font-size: 20px">
                    <b>Introduction</b>
                </span>
            </div>
            <hr />
            <!--  Intro form -->
            <div class="text-left pb-4">
                <textarea id="intro" name="intro" type="text" class="input_textarea" disabled
                    style="height: 325px">{{$user->profile->self_intro}}</textarea>
            </div>
        </div>

        <div style="height: 50px"></div>
        <div class="col-md-12 row alternates" style="max-width: 900px !important;">
            @foreach($service as $p)
            <div class="col-md-3 pb-4">
                <div>
                    <img style="width: 100%; height: 100px; object-fit: cover"
                        src="{{Request::root().'/uploads/products/'.$p->thumbnail_image}}" />
                    <div style="height: 45px; overflow-y: hidden; font-size: 12px">
                        <span>
                            <b>{{$p->title}}</b>
                        </span>
                    </div>
                    <div style="height: 30px; overflow-y: hidden; font-size: 15px; text-align: right">
                        <b>{{$p->price}}円</b>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
    </div>
</section>




@stop

@section('custom_js')

<script>
    var  hide = document.getElementById("introHide");
    var input = document.getElementById("intro")
        if(input.value == ""){
            hide.style.display = "none";
         }
</script>
<script>
    var  hide = document.getElementById("phoneHide");
    var input = document.getElementById("phone")
        if(input.value == ""){
            hide.style.display = "none";
         }
</script>
<script>
    var  hide = document.getElementById("addressHide");
    var input = document.getElementById("address")
        if(input.value == ""){
            hide.style.display = "none";
         }
</script>
@stop
