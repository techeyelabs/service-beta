
@extends('systems.main')

@section('custom_css')
<style media="screen" type="text/css">
	.title:hover {
		color: #618ca9;
	}

	.category:hover {
		color: #618ca9;
	}

	.res_banner {
		padding-left: 5%;
		padding-right: 5%;
		height: 650px;
	}

	@media only screen and (max-width: 768px) {
		.res_banner {
			height: auto !important
		}
	}
</style>
@stop

@section('content')




<div style="width: 100%; height: 10px">&nbsp;</div>
<div class="res_banner p-0">
	@include('systems.banner')
</div>
<br />
<section class="project_list flex_cont p-3" style="">
	<div class=" row col-md-12 alternates">
		<div class="col-md-2"></div>
        <div class="col-md-4 mr-0 text-right">
        <div>
                <a href="{{route('add-service')}}">
                    <button class="extra_banner_top btn uBtnB " style="font-size: 25px !important; width:80% !important;"><b>Create Service</b></button>
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div>
                <a href="{{route('add-request')}}">
                    <button class="extra_banner_top btn uBtnB " style="font-size: 25px !important; width:80% !important;"><b>Request</b></button>
                </a>
            </div>
        </div>
		<div class="col-md-2"></div>
	</div>
</section>
<br />
<section class="project_list mb-5 pb-5">
	<div class="col-md-12 alternates">
		<div class="col-sm-12">
        @if($latest)
			<div class="text-left pl-1">
				<h2 style="font-size: 1.5rem;">Latest Projects</h2>
				<div style="height: 20px"></div>
				<div class="row flex_cont pr-1">
                @foreach($latest as $p)
					<div class="col-md-3">
					@include('systems.project-all')
					</div>
                    @endforeach
				</div>
			</div>
			<button class="btn uBtn" style="float: right;" onclick="location.href='#';">See More..</button>
	    @endif
        @if($donation)
			<div class="text-left pl-1 pt-5" id="topDonation">
				<h2 style="font-size: 1.5rem;">Top Donation</h2>
				<div style="height: 20px"></div>
				<div class="row flex_cont pr-1" id="td">
                @foreach($donation as $p)
					<div class="col-md-3">
						@include('systems.project-all')
					</div>
                    @endforeach
				</div>
			</div>
			<button class="btn uBtn" style="float: right;" onclick="location.href='#';">See More..</button>
            @endif
            @if($endSoon)
			<div class="text-left pl-1 pt-5">
				<h2 style="font-size: 1.5rem;">End Soon</h2>

				<div style="height: 20px"></div>
				<div class="row flex_cont pr-1">
                @foreach($endSoon as $p)
					<div class="col-md-3">
						@include('systems.project-all')
					</div>
                    @endforeach
				</div>
			</div>
			<button class="btn uBtn" style="float: right;" onclick="location.href='#';">See More..</button>
            @endif
            @if($startSoon)
			<div class="text-left pl-1 pt-5">
				<h2 style="font-size: 1.5rem;">Coming Soon</h2>

				<div style="height: 20px"></div>
				<div class="row flex_cont pr-1">
                @foreach($startSoon as $p)
					<div class="col-md-3">
						@include('systems.project-all')
					</div>
                    @endforeach
				</div>
			</div>
			<button class="btn uBtn pb-4" style="float: right;" onclick="location.href='#';">See More..</button>
            @endif





	</div>
	</div>
</section>
@stop

@section('custom_js')
@yield('sort_js')

<!-- <script>
	var topDonation = document.getElementById("topDonation");
	var td = document.getElementById("td")
	if (td.value == "") {
		topDonation.style.display = "none";
	}
</script> -->

<script type="text/javascript">
	$('.banner_slider').slick({
		centerMode: true,
		centerPadding: '60px',
		slidesToShow: 1,
		responsive: [{
				breakpoint: 768,
				settings: {
					arrows: false,
					centerMode: true,
					centerPadding: '40px',
					slidesToShow: 3
				}
			},
			{
				breakpoint: 480,
				settings: {
					arrows: false,
					centerMode: true,
					centerPadding: '40px',
					slidesToShow: 1
				}
			}
		]
	});
</script>
@stop
