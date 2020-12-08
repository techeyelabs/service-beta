
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
	<div class="col-md-12 alternates">
		<div class="col-sm-12 ml-1 mr-1">
			<div class=" text-center  pl-2 pr-2">
				<div><a href="#"><button class="extra_banner_top btn uBtnB"
							style="font-size: 25px !important"><b>Start Raising</b></button></a></div>
			</div>
		</div>
	</div>
</section>
<br />
<section class="project_list mb-5 pb-5">
	<div class="col-md-12 alternates">
		<div class="col-sm-12">

			<div class="text-left pl-1">
				<h2 style="font-size: 1.5rem;">Latest Projects</h2>
				<div style="height: 20px"></div>
				<div class="row flex_cont pr-1">

					<div class="col-md-3">
					@include('systems.project-all')
					</div>

				</div>
			</div>
			<button class="btn uBtn" style="float: right;" onclick="location.href='#';">See More..</button>

			<div class="text-left pl-1 pt-5" id="topDonation">
				<h2 style="font-size: 1.5rem;">Top Donation</h2>
				<div style="height: 20px"></div>
				<div class="row flex_cont pr-1" id="td">

					<div class="col-md-3">
						@include('systems.project-all')
					</div>

				</div>
			</div>
			<button class="btn uBtn" style="float: right;" onclick="location.href='#';">See More..</button>

			<div class="text-left pl-1 pt-5">
				<h2 style="font-size: 1.5rem;">End Soon</h2>

				<div style="height: 20px"></div>
				<div class="row flex_cont pr-1">

					<div class="col-md-3">
						@include('systems.project-all')
					</div>

				</div>
			</div>
			<button class="btn uBtn" style="float: right;" onclick="location.href='#';">See More..</button>


			<div class="text-left pl-1 pt-5">
				<h2 style="font-size: 1.5rem;">Coming Soon</h2>

				<div style="height: 20px"></div>
				<div class="row flex_cont pr-1">

					<div class="col-md-3">
						@include('systems.project-all')
					</div>

				</div>
			</div>
			<button class="btn uBtn pb-4" style="float: right;" onclick="location.href='#';">See More..</button>






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
