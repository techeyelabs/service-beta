@extends('systems.main')

@section('custom_css')
	<style type="text/css">
		.wizard > .steps > ul > li {
		    width: 20%;
		}
		.body{

		}
		.amount{
			border: 1px solid black !important;
			padding: 5px;
		}
		.no-border{
			border: none;
		}
		.box{
			border: 1px solid black !important;
		}
		.padding{
			padding: 10px;
		}
		.heading:after{
			display: block;
			height: 3px;
			background-color: #80b9f2;
			content: "";
			width: 100%;
			margin: 0 auto;
			margin-top: 10px;
			margin-bottom: 30px;
		}
		.bg-dark{
			background-color: #E4E4E4;
		}
		.div-radius{
			border: 3px solid #eaebed;
			border-radius: 5px;
		}
		.div-radius1{
			/* border: 3px solid #eaebed; */
			border-radius: 5px;
		}
		.horizontal:after{
			display: block;
			height: 2px;
			background-color: #999;
			content: "";
			width: 100%;
			margin: 0 auto;
			margin-top: 10px;
			margin-bottom: 45px;
		}
		.bg-danger{
			background-color: #ffe3da !important;
		}

	.project-item{
		position: relative;
	}
	.project_status{
		position: absolute;
		top: 15px;
		left: 1px !important;
		width: auto;
		padding: 5px;
		padding-left: 15px;
		padding-right: 15px;
		text-align: center;
		background-color: #ff6540;
	}
	.icon-info{
		border: 2px solid #ff3300;
		padding-right: 10px;
		padding-left: 10px;
		padding-top: 1px;
		padding-bottom: 1px;
		border-radius: 50%;
		color: #ff3300;
		background-color: #ffffff;
	}
	.bg-white{
		background-color:#fff;
	}
	.content-inner-blue:before{
		display: block;
		height: 2px;
		background-color: #81ccff;
		content: "";
		width: 100%;
		margin: 0 auto;
		margin-top: 0px;
		margin-bottom: 0px;
	}

.content-inner-arrow{
	/*-webkit-clip-path: polygon(0 0, 100% 0, 100% 82%, 51% 100%, 0 82%);
	clip-path: polygon(0 0, 100% 0, 100% 82%, 51% 100%, 0 82%);*/

}
.bg-blue{
	background-color: #618ca9;
}
.no-container {
margin-left: 0 !important;
margin-right: 0 !important;
}
	.mt40{
		margin-top: 40px !important;
	}
	#shareIcons{
		padding-bottom: 20px;
	}
	#shareIcons a{
		text-decoration: none;
		padding-top: 5px;
		padding-bottom: 5px;
	}
	.details_btm_arrow{
		position: relative;
		margin-bottom: 25px !important;
	}
	.breadcrump{
		background-color: #F1F1F1;
	}
	.breadcrump li a{
		color: #000;

	}
	.animated_butt{
		display: inline-block;
		color: #fff;
		padding: 20px 30px;
		border-radius: 5px;
		box-shadow: 0  17px 10px -10px rgba(0, 0, 0, 0.4);
		cursor: pointer;
		transition: all ease-in-out 300ms;

	}
	.animated_butt:hover{
		box-shadow: 0  37px 20px -20px rgba(0, 0, 0, 0.2);
		transform: translate(0, -10px);
	}
	.special:hover{
		background-color: #eceeef
	}
	.imageHolder {
    height: 500px;
    background-color:white;
    margin:10px;
    display:inline-block;
    float:left;
    position:relative;
	}
	/* max-width: 100%;
    max-height:100%;
    margin: auto;
    position:absolute;
    top:0;
    left:0;
    right:0;
    bottom:0; */
	.imageHolder img {

	vertical-align: middle;
    text-align: center;
    display: table-cell;
    margin-top: 5px!important;
	}
    .extra_div {
		background-color: white;
		/* border: 1px solid #f1f1f1; */
		border-radius: 1%;
		padding: 0%;
		box-shadow: 0 3px 7px 0 rgba(0, 0, 0, 0.2), 0 5px 15px 0 rgba(0, 0, 0, 0.19);
	}
	.alternates1100px{
		margin: 0 auto !important;
        max-width: 1100px !important;
	}
	</style>
@stop


@section('content')

<div style="padding-right: 10%; padding-left: 10%" class="small_screen_drowpdownpads">
		<div class="col-md-12 project_details_area">
			<div class="col-md-12 text-center pt-2" style="font-size: 20px"><h3>{{$service->title}}</h3></div>
			<div class="col-md-12 text-center " style="">
				<a href="{{route('visit-profile',['id'=>$service->user->id])}}"><p style="font-size:20px;">{{$service->user->first_name}} {{$service->user->last_name}}</p></a>
			</div>
			<div class="row flex_cont">
				<div class="col-md-7 col-sm-12 pr-2">
					<div class="col-12 imageHolder" >
						<img src="{{url('uploads/products/'.$service->thumbnail_image)}}" alt="" class="">
					</div>
				</div>
				<div class="col-md-5 col-sm-12 pl-2">
					<div class="row">
						<div class="col-md-8 col-sm-6 category_favourite pt-3">
							<h6 class="" style="font-size:14px; color:#bfc5cc;"> <span style="color:#bfc5cc;"> 	<i class="fa fa-tag"></i> <a href="#"> {{  $service->category->cat_name }} </a>
							</h6>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 col-sm-6 category_favourite pt-3">
							<h6 class="" style="font-size:48px; color:#000000;">
                            {{$service->price}} BDT
							</h6>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-sm-3 category_favourite pt-3">
                         <button class="btn uBtn" style="float: left; width:100%!important;">Follow</button>
						</div>
						<div class="col-md-4 col-sm-3 category_favourite pt-3">
                         <button class="btn uBtn" style="float: left; width:50%!important;  color:black; opacity: 1; pointer-events: none;" disabled>50</button>
						</div>
					</div>
					<div class="row mt-3 pl-2">
                        <div class="col-md-4 pt-2 stars">
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                        </div>
                        <div class="col-md-4 col-sm-3 pl-2">
                         <button class="btn uBtn" style="float: left; width:50%!important;  color:black; opacity: 1; pointer-events: none;" disabled>4.8</button>
						</div>
					</div>
					<div class="mt-5">
						<div class="col-md-12 col-sm-12 mr-0 mt-3 p-0 assist_project_btn_area">
								<a id= "" title="purchase" href="" class="bg-blue text-white btn btn-lg btn-block animated_butt" name="button" style=" height:70px;padding-top: 3%; font-size: 25px;">Purchase</a>
						</div>
					</div>
					<div class="">
						<div class="col-md-12 col-sm-12 mr-0 mt-4 p-0 assist_project_btn_area">
								<a id="" href= "{{route('add-request')}}" title="purchase" href="" class="bg-blue text-white btn btn-lg btn-block animated_butt" name="button" style=" height:70px;padding-top: 3%; font-size: 25px;">Request</a>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
<div><hr></div>

<div class="alternates1100px project_details_bottom_info flex_cont">
	<div style="padding-right: 10%; padding-left: 10%" class="small_screen_drowpdownpads">
		<div>
			<div class="mt-5">
				<div class="row">
					<div class="col-md-12">
						<div class="row inner">
							<div class="col-md-12  mb-5 ">
								<div class="mb-3">
									<div class="bg-white p-2">
										<h4 class="pb-2 font-weight-bold">Summary</h4>
										<p>{{$service->summary}}</p>
									</div>
								</div>
								<div class="mb-3">
									<div class="bg-white p-2">
										<div class="mb-4">
											<div class="col-12 imageHolder">
												<img src="{{url($service->service_image)}}" alt=""
													class="img-fluid">
											</div>
										</div>
									</div>
								</div>

								<div class="mb-3 ">
									<div class="bg-white p-2">
										<h4 class="pb-2 font-weight-bold">Service Details</h4>
										<div class="row">
											<span class="col-md-12">
											{{$service->service_details}}
											</span>
										</div>

										<div class="col-12 imageHolder">
											<img src="{{url($service->additional_details_image)}}" alt="" class="img-fluid">
										</div>

									</div>
								</div>

								<div class="mb-3 ">
									<div class="bg-white p-2">
										<h4 class="pb-2 font-weight-bold">Additional Details</h4>
										<div class="row">
											<span class="col-md-12">
											{{$service->additional_details}}
											</span>
										</div>
									</div>
								</div>

							</div>
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
