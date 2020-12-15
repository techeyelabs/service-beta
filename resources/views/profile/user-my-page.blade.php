@extends('systems.main')

@section('custom_css')

<style type="text/css">

</style>
@stop

@section('content')


<section class="auth_form_area pt-5">
	<div class="col-md-12 alternates" style="min-height: 500px">
		<div class="col-sm-12 bg-white area_auth">
			<div class="col-md-12 col-sm-12 part_1 text-left">

                <!-- Banner and Profile pic form -->
				<form id="image_upload" action="{{route('update-pic')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <?php
                        $banner = parse_url(Auth::user()->banner);
                        if(isset($banner['host'])) $banner = Auth::user()->banner;
                        else $banner = Request::root().'/uploads/'.Auth::user()->banner;
                    ?>
                    <!-- Banner Div -->
					<div class="">
                        @if(Auth::user()->banner)
                        <img class="banner_image_fill" src="{{$banner}}" onerror="this.style.opacity='4'" />
                        @else
                        <div class="banner_image_null"></div>
                        @endif
                        <div style="height:0px;overflow:hidden">
                            <input  id="ban_p" name="ban_p" type="file" onchange="doAfterSelectImage(this)" accept="image/*"/>
                            <button type="button" class="btn" id="btncp" onclick="choosecpFile();" >
                                <i class="fa fa-camera" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <?php
                        $pic = parse_url(Auth::user()->pic);
                        if(isset($pic['host'])) $pic = Auth::user()->pic;
                        else $pic = Request::root().'/uploads/'.Auth::user()->pic;
                    ?>
                        <!-- Profile Pic Div -->
                    <div class="">
                        <img class="small_profile_image" style="border-radius: 50%; width: 175px; margin-top: -60px; margin-left: 22%; border: 5px solid white; object-fit:cover;" src="{{$pic}}" alt="...">
                        <div style="height:0px;overflow:hidden">
                            <input id="dis_p" name="dis_p" type="file" onchange="doAfterSelectImage(this)"  accept="image/*"/>
                            <button type="button" class="btn" id="btndp" onclick="chooseFile();">
                                <i class="fa fa-camera" aria-hidden="true"></i>
                            </button>
                        </div>
                     </div>
                    </form>
                        <!-- Name,points and social network div -->
						<div class="col-md-7" style="display: none; margin-left:35%; ">
							<div>
								<b>
									<i>{{ $user->first_name }} {{$user->last_name}}</i>
								</b>
							</div>

                            <div>
                                @if($user->facebook_id =='')
                                    <a>
                                    <img class="" style="border-radius: 50%; width: 35px" src="/uploads/profile/facebook_gray.png" alt="...">
                                    </a>
                                @else
                                <a>
                                    <img class="" style="border-radius: 50%; width: 35px" src="/uploads/profile/facebook_blue.png" alt="...">
                                    </a>
                                @endif
                                @if($user->google_id =='')
                                <a>
                                    <img class="" style="border-radius: 50%; width: 35px" src="/uploads/profile/google_gray.png" alt="...">
                                    </a>
                                @else
                                <a>
                                    <img class="" style="border-radius: 50%; width: 35px" src="/uploads/profile/google_blue.png" alt="...">
                                    </a>
                                @endif
                                @if($user->twitter_id =='')
                                <a>
                                    <img class="" style="border-radius: 50%; width: 35px" src="/uploads/profile/twitter_gray.png" alt="...">
                                    </a>
                                @else
                                    <a>
                                        <img class="" style="border-radius: 50%; width: 35px" src="/uploads/profile/twitter_blue.png" alt="...">
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
					<hr/>
                    <!-- My Profile Form  -->
					<form id="basic_form" action="{{route('update-basic')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}

						<div class="text-left pb-4">
							<p style="margin-bottom: 0px !important">First Name</p>
							<input id="fname" name="fname" type="text" class="input_boxes" value="{{$user->first_name}}"/>
						</div>
						<div class="text-left pb-4">
							<p style="margin-bottom: 0px !important">Last Name</p>
							<input id="lname" name="lname" type="text" class="input_boxes" value="{{$user->last_name}}"/>
						</div>
						<div class="text-left pb-4">
							<p style="margin-bottom: 0px !important">E mail</p>
							<input id="email" name="email" type="text" class="input_boxes" value="{{$user->email}}"/>
						</div>
						<div class="text-left pb-4">
							<p style="margin-bottom: 0px !important">Phone</p>
							<input id="phone" name="phone" type="text" class="input_boxes" value="{{$user->profile->phone_no}}"/>
						</div>
						<div class="text-left pb-4">
							<p style="margin-bottom: 0px !important">Address</p>
							<textarea id="address" name="address" type="text" class="input_textarea" disabled>{{$user->profile->address}}</textarea>
						</div>
					</form>
					<div class="text-right pb-4">
						<button id="editbtn_basic" class="btn uBtn" type="submit" class="extra_banner_top" onclick="makeeditable('basic_form', 'submitbtn_basic', 'editbtn_basic');
                         changebasicformbordercolor();">
                        Edit
                    </button>
						<button id="submitbtn_basic" class="btn uBtn" type="submit" class="extra_banner_top" style="display: none" onclick="update('basic_form', 'editbtn_basic')">
                        Submit
                    </button>
					</div>
				</div>
                <!--  Introduction div -->
				<div style="height: 50px"></div>
				<div class="col-md-12 alternates" style="max-width: 500px !important;">
					<div class="text-center pb-3">
						<span style="font-size: 20px">
							<b>Introduction</b>
						</span>
					</div>
					<hr/>
                    <!--  Intro form -->
					<form id="intro_form" action="{{route('intro-basic')}}" method="POST">
						<div class="text-left pb-4">
                        {{ csrf_field() }}

							<textarea id="intro"  name="intro" type="text" class="input_textarea" disabled   style="height: 325px">{{$user->profile->self_intro}}</textarea>
						</div>
					</form>
					<div class="text-right pb-4">
                        <button id="editbtn_intro" type="submit" class="extra_banner_top btn uBtn" onclick="makeeditable('intro_form', 'submitbtn_intro', 'editbtn_intro');
                        changeintroformbordercolor();">
                        Edit
                    </button>
						<button id="submitbtn_intro" type="submit" class="extra_banner_top btn uBtn" style="display: none" onclick="update('intro_form', 'editbtn_intro')">
                        Submit
                    </button>
					</div>
				</div>
				<div style="height: 50px"></div>
                <!--   Payment Div  -->
				<div class="col-md-12 alternates" style="max-width: 500px !important;">
					<div class="text-center pb-3">
						<span style="font-size: 20px">
							<b>Payment Information</b>
						</span>
					</div>
					<hr/>
                    <!--   Payment form -->
					<form id="auth_form" action="{{route('update-payment')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}

						<div class="text-left pb-4">
							<p style="margin-bottom: 0px !important">Bank Name</p>
                        {{--
							<input type="text" style="border: 1px solid #c7c7c7; width: 100%; height: 40px"/>--}}
							<select class="minimal profile_dropdowns" name="bank" id="bank" disabled>
                            @foreach($banks as $b)
								<option value="{{$b->id}}"
									<?php if($authentics->bank == $b->id) {echo 'selected="selected"';} ?>>{{$b->bank_name}}
								</option>
                            @endforeach
							</select>
						</div>
						<div class="text-left pb-4">
							<p style="margin-bottom: 0px !important">Branch Name</p>
							<input id="branch" name="branch" type="text" class="input_boxes" value="{{$authentics->branch_name}}"/>
						</div>
						<div class="text-left pb-4">
							<p style="margin-bottom: 0px !important">District</p>
							<select class="minimal profile_dropdowns" name="district" id="district" disabled>
                            @foreach($districts as $b)
								<option value="{{$b->id}}"
									<?php if($authentics->district == $b->id) {echo 'selected="selected"';} ?>>{{$b->district_name}}
								</option>
                            @endforeach
							</select>
						</div>
						<div class="text-left pb-4">
							<p style="margin-bottom: 0px !important">Account Name</p>
							<input id="acc_name" name="acc_name" type="text" class="input_boxes" value="{{$authentics->account_name}}"/>
						</div>
						<div class="text-left pb-4">
							<p style="margin-bottom: 0px !important">Account No.</p>
							<input id="acc_no" name="acc_no" type="text" class="input_boxes" value="{{$authentics->account_no}}"/>
						</div>
						<div class="text-left pb-4">
							<p style="margin-bottom: 0px !important">bKash Number</p>
							<input id="bkash_no" name="bkash_no" type="text" class="input_boxes" value="{{$authentics->bkash}}"/>
						</div>
                        <!--   NID Pic Row  -->


				</div>
                <div style="height: 50px"></div>
                <div class="col-md-12 alternates row" style="max-width: 900px !important;">
                            <!--   NID front Pic column  -->
							<div id="nfront" class="col-md-5 pb-3  " style="display: block; text-align: right !important">
								<p style="margin-bottom: 0px !important">NID Front</p>
                                <input id="nid_front"  name="nid_front" type="file"   accept="image/*" style="visibility:hidden;"/>
                                <div class="nid_fit" >
                                    @if($authentics->nid_front)
                                    <img class="nid_fill" style="margin-right:0px; " src="{{Request::root().'/uploads/'.$authentics->nid_front}}" />
                                </div>
                                @else
                                <div class="nid_null"></div>
                                @endif
								<button type="button" class="btn" id="btnNidf"  onclick="chooseNidFrontFile();" disabled>
									<i class="fa fa-camera" aria-hidden="true"></i>
								</button>
                            </div>

                            <div class="col-md-2"></div>

                              <!--   NID back Pic column  -->
							<div id="nback" class="col-md-5 text-left pb-3" style="display: block" >
								<p style="margin-bottom: 0px !important">NID Back</p>
                                <input id="nid_back"  name="nid_back" type="file"    accept="image/*" style="visibility:hidden;"/>
                                <div class="nid_fit" >
                                    @if($authentics->nid_back)
                                    <img class="nid_fill " style="" src="{{Request::root().'/uploads/'.$authentics->nid_back}}" />
                                </div>
                                @else
                                <div class="nid_null"></div>
                                @endif
								<button type="button" type class="btn" id="btnNidb"  onclick="chooseNidBackFile();" disabled>
									<i class="fa fa-camera" aria-hidden="true"></i>
								</button>
							</div>
                            </form>

                            <div class="col-md-12 text-right pb-4 " style="margin-top:20px;  ">
                                <button id="editbtn_auth" type="submit" class="extra_banner_top btn uBtn " style="margin-right:35px"
                                     onclick="makeeditable('auth_form', 'submitbtn_auth', 'editbtn_auth'); changeauthformbordercolor();">
                                     Edit
                                </button>
                                <button id="submitbtn_auth" type="submit" class="extra_banner_top btn uBtn" style="display: none" onclick="update('auth_form', 'editbtn_auth') ">
                                     Submit
                                 </button>
					        </div>
					    </div>
				<div style="height: 50px"></div>
				<div class="col-md-12 row alternates" style="max-width: 900px !important;">
                @foreach($projects as $p)
					<div class="col-md-3 pb-4" >
						<div style="border: 1px solid #d4d4d4; height:220px;">
							<img style="width: 100%; height: 100px; object-fit: cover" src="{{Request::root().'/uploads/products/'.$p->thumbnail_image}}" />
							<div style=" font-size: 12px; max-width: 180px; padding: 10px ;">
								<span class="mypage_project">
									<b class="projectTitle">{{$p->title}}</b>
								</span>
							</div>
							<div style="height: 30px; overflow: hidden; font-size: 15px; text-align: center; ">
								<b >{{$p->price}} BDT</b>
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
function changebasicformbordercolor()
    {
        $("form#basic_form :input").each(function(){
            $(this).css("border", "2px solid #000000");
        });


    }
function changeintroformbordercolor()
    {

        $("form#intro_form :input").each(function(){
            $(this).css("border", "2px solid #000000");
        });

    }
function changeauthformbordercolor()
    {

        $("form#auth_form :input").each(function(){
            $(this).css("border", "2px solid #000000");
        });

    }
</script>
    <!-- Textarea Enable Function -->
<script>
    function textareaFun(){
        document.getElementById("address").disabled=false;
    }
</script>
	<script>
        function nidFrontFun(){
            document.getElementById("btnNidf").disabled=false;
        }
        </script>
	<script>
        function nidBackFun(){
            document.getElementById("btnNidb").disabled=false;
        }
        </script>
	<script>
        function introareaFun(){
            document.getElementById("intro").disabled=false;
        }
        </script>
	<script>
        function bankareaFun(){
            document.getElementById("bank").disabled=false;
        }
        </script>
	<script>
        function districtareaFun(){
            document.getElementById("district").disabled=false;
        }
        </script>

          <!--   Profile,Banner,NID front and back pic submit  -->
	<script>
        function doAfterSelectImage(input){
            $('#image_upload').submit();
        }
        function doAfterSelectNidImage(input){
            $('#auth_form').submit();
        }
        </script>

          <!--   Enable Pop up option for image upload  -->
	<script>
        function chooseFile() {
            document.getElementById("dis_p").click();
        }
        </script>
	<script>
        function choosecpFile() {
            document.getElementById("ban_p").click();
        }
        </script>
	<script>
        function chooseNidFrontFile() {
            document.getElementById("nid_front").click();
        }
        </script>
	<script>
        function chooseNidBackFile() {
            document.getElementById("nid_back").click();
        }
        </script>
	<script>
    // Form control fuction
    function makeeditable(x, y, z){
        $('#'+x+' input').attr("disabled", false);
        $('#'+z).hide();
        $('#'+y).show();
        if(x == 'intro_form'){
            introareaFun();
        }

        if(x == 'auth_form'){
            nidFrontFun();
            nidBackFun();
            bankareaFun();
        districtareaFun();
        }
        if(x == 'basic_form'){
            textareaFun();
        }
    }

    function update(x, y){
        $('#'+x).submit();
    }

</script>
	<script type="text/javascript">
    var error = $('#getError').val();
    $(window).on('load',function(){
    console.log('error = ' + error);
        if (error == 1) {
            $('#myModal').modal('show');
        }
    });
</script>
	<script type="text/javascript">
    $(document).ready(function(){
        // disabling the inputs
        $('#basic_form input').attr("disabled", true);
        $('#intro_form input').attr("disabled", true);
        $('#auth_form input').attr("disabled", true);


        $('.msg_send_btn').on('click', function(e){
            var user_id = $(this).attr('data-user_id');
            var user_name = $(this).attr('data-project_username');


            $('#to_id').val(user_id);
            $('#project_user_name').val(user_name);
            $('#send-message').modal('show');
            //$('#send-message').addClass('show');
        });

        $('.rating_btn').on('click', function(){
            var product_id = $(this).attr('data-product-id');
                var my_rate = parseInt($(this).attr('data-my-rate'));
            // console.log(product_id);
            $('#get_product_id').val(product_id);
            $('#get_my_rate').val(my_rate);

            if (my_rate == 1) {
                $('#one').addClass('active');
                $('#two').removeClass('active');
                $('#three').removeClass('active');
                $('#four').removeClass('active');
                $('#five').removeClass('active');
            }else if (my_rate == 2) {
                $('#one').removeClass('active');
                $('#two').addClass('active');
                $('#three').removeClass('active');
                $('#four').removeClass('active');
                $('#five').removeClass('active');
            }else if (my_rate == 3) {
                $('#one').removeClass('active');
                $('#two').removeClass('active');
                $('#three').addClass('active');
                $('#four').removeClass('active');
                $('#five').removeClass('active');
            }else if (my_rate == 4) {
                $('#one').removeClass('active');
                $('#two').removeClass('active');
                $('#three').removeClass('active');
                $('#four').addClass('active');
                $('#five').removeClass('active');

            }else if (my_rate == 5) {
                $('#one').removeClass('active');
                $('#two').removeClass('active');
                $('#three').removeClass('active');
                $('#four').removeClass('active');
                $('#five').addClass('active');
            }

        });

        $('.rating').on('click', function(){
            var rate = $(this).attr('data-rating');
            $('#get_rating').val(rate);
            // console.log($('#get_rating').val());
            // $(this).addClass('active');
            var getId = $(this).attr('id');
            console.log(getId);
            if (getId == 'one') {
                $('#one').addClass('active');
                $('#two').removeClass('active');
                $('#three').removeClass('active');
                $('#four').removeClass('active');
                $('#five').removeClass('active');
            }else if (getId == 'two') {
                $('#one').removeClass('active');
                $('#two').addClass('active');
                $('#three').removeClass('active');
                $('#four').removeClass('active');
                $('#five').removeClass('active');
            }else if (getId == 'three') {
                $('#one').removeClass('active');
                $('#two').removeClass('active');
                $('#three').addClass('active');
                $('#four').removeClass('active');
                $('#five').removeClass('active');
            }else if (getId == 'four') {
                $('#one').removeClass('active');
                $('#two').removeClass('active');
                $('#three').removeClass('active');
                $('#four').addClass('active');
                $('#five').removeClass('active');
            }else if (getId == 'five') {
                $('#one').removeClass('active');
                $('#two').removeClass('active');
                $('#three').removeClass('active');
                $('#four').removeClass('active');
                $('#five').addClass('active');
            }

        });
    });
</script>


@stop
