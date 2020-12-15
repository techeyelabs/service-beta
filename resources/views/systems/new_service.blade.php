@extends('systems.main')

@section('custom_css')
<style type="text/css">
	input[type="date"] {
		position: relative;
		padding-right: 15px;
	}

	.modal-open {
		overflow: auto;
		overflow-y: hidden;
	}
	.modal-open #new-service {
    -webkit-filter: blur(5px) grayscale(90%);
    filter: blur(5px) grayscale(90%);
}

	.modal.in {
		pointer-events: none;
	}

	.modal-content {
		pointer-events: all;
	}

	.modal-backdrop {
		display: none;
	}

</style>
@stop


@section('ecommerce')

@stop

@section('content')

<div class="alternates" id="new-service">
	<div class="mt20">
		<div class="row flex_cont">
			<div class="col-md-12 common_box_body" style="padding-left: 5%; padding-right: 5%">
				<h1 class="text-center page_title_product_register">Service Upload</h1>
				<form id="new-service-form" name="addNewServiceForm" action="#" class="mt20"
					method="post" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="mt20 container">
						<section class="mt-3">
							<div class="form-group col-md-12 row">
								<div class="col-md-3">
									<label><b>Service Title</b></label>
									<span class="" style="color:red;">*</span>
								</div>
								<div class="col-md-9" id="title">
									<input type="text" class="form-control" id="title1" placeholder="" name="title"
										style="width: 100%" maxlength="100">
									<div class="error" id="titleErr"></div>
								</div>
							</div>
							<div class="col-md-12">&nbsp;</div>
							<div class="form-group col-md-12 row">
								<div class="col-md-3">
									<label for="category"><b>Category(classification)</b></label>
									<span class="" style="color:red;">*</span>
								</div>
								<div class="col-md-9">
									<!-- onchange="catChange" -->
									<select class="form-control spInputs" id="categoryPS" name="category"
										onchange="selectCategory(this)">
										<option value="">Select</option>
										@foreach($categories  as $c)
										<option id="catOpt" value="{{$c->id}}">{{$c->cat_name}}</option>
										@endforeach
									</select>
									<div class="error" id="categoryErr"></div>
								</div>
							</div>
							<div class="col-md-12">&nbsp;</div>
							<div class="form-group col-md-12 row">
								<div class="col-md-3">
									<label><b>Thumbnail Image</b>
										<span class="text-danger"></span>
									</label>
									<span class="" style="color:red;">*</span>
								</div>
								<div class="col-md-9">
									<button class="btn btn-sm btn-default" id="upfile1">Select Image</button>
									<input type="file" id="file1" class="col-12 btn d-none" name="thumbnail_image">
									<span id="select_file" class="ml-3">Not Selected</span>
									<div class="error" id="thumbnail_imageErr"></div>
									<span id="no_file" style="font-size: 11px; color: red"></span>
								</div>
							</div>
							<div class="col-md-12">&nbsp;</div>
							<div class="form-group col-md-12 row">
								<div class="col-md-3">
									<label for=""><b>Serivce Price</b></label>
									<span class="" style="color:red;">*</span>
								</div>
								<div class="col-md-9">
									<input type="text" class="form-control spInputs" id="price" placeholder=""
										name="price" min="0" style="width:27%;" />
									<div class="error" id="priceErr"></div>
								</div>

							</div>

							<div class="col-md-12">&nbsp;</div>
							<div class="form-group col-md-12 row">
								<div class="col-md-3">
									<label><b>Affiliate Reward</b></label>
                                    <span class="" style="color:red;">*</span>
								</div>
								<div class="col-md-5" ;">
									<!-- onchange="catChange" -->
									<select class="form-control spInputs" id="rewardPS" name="reward" style="width:50%"
										onchange="selectReward(this)">
										<option value="">Select </option>
                                        @foreach($affiliate_reward as $f)
										<option id="catOpt" value="{{$f->id}}">{{$f->percentage}}</option>
                                        @endforeach
									</select>
									<div class="error" id="rewardErr"></div>
								</div>

                                <div class="col-md-4 row padingRemover paddingRightRemove">
                                <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="self_purchasable" name="self_purchasable" value="0">
                                <label class="form-check-label" for="exampleCheck1"><b>Self-purchasable</b>(affiliator)</label>
                                <span class="" style="color:red;">*</span>
                                 </div>
                                </div>
							</div>
							<div class="col-md-12">&nbsp;</div>
							<div class="form-group col-md-12 row">
								<div class="col-md-3">
									<label for="purpose"><b>Summary</b>
									</label>
									<span class="" style="color:red;">*</span>
								</div>
								<div class="col-md-9">
									<textarea type="text" id="summary" class="form-control  col-12" rows="6" cols="40"
										placeholder="" name="summary" maxlength="1000"></textarea>
									<div class="error" id="summaryErr"></div>
								</div>
							</div>
							<div class="col-md-12">&nbsp;</div>
							<div class="form-group col-md-12 row">
								<div class="col-md-3">
									<label for="purpose_image"><b>Service Image</b>
									</label>
									<span class="" style="color:red;">*</span>
								</div>
								<div class="col-md-9">
									<button class="btn btn-sm btn-default" id="purposeupfile1">Select Image</button>
									<input type="file" id="purposefile1" class="col-12 btn   d-none"
										name="service_image">
									<span id="purpose_select_file" class="ml-3">Not Selected</span>
									<div class="error" id="service_imageErr"></div>
									<span id="pupose_no_file" style="font-size: 11px; color: red"></span>
								</div>

							</div>
							<div class="col-md-12">&nbsp;</div>
							<div class="form-group col-md-12 row">
								<div class="col-md-3">
									<label for="description"><b>Service Details </b>
									</label>
									<span class="" style="color:red;">*</span>
								</div>
								<div class="col-md-9">
									<textarea name="service_details" id="service_details" rows="6" cols="60"
										class="form-control  col-12" maxlength="5000"></textarea>
									<div class="error" id="service_detailsErr"></div>
								</div>
							</div>
                            <div class="col-md-12">&nbsp;</div>
							<div class="form-group col-md-12 row">
								<div class="col-md-3">
									<label for="detail_image"><b>Additional Details Image</b>
									</label>

								</div>
								<div class="col-md-9">
									<button class="btn btn-sm btn-default" id="detailupfile1">Select Image</button>
									<input type="file" id="detailfile1" class="col-12 btn   d-none" name="additional_details_image">
									<span id="detail_select_file" class="ml-3">Not Selected</span>
									<!-- <div class="error" id="detail_imageErr"></div> -->
									<span id="detail_no_file" style="font-size: 11px; color: red"></span>
								</div>
							</div>
							<div class="col-md-12">&nbsp;</div>
							<div class="form-group col-md-12 row">
								<div class="col-md-3">
									<label for="budget_usage_breakdown"><b>Additional Details</b>
									</label>
								</div>
								<div class="col-md-9">
									<textarea id="additional_details" name="additional_details" rows="8"
										cols="60" class="form-control  col-12 breakdownM" maxlength="5000"></textarea>
								</div>
							</div>
						</section>
						<section>
							<h4 class="text-center mt20 mt-5 mb-4">
								<!-- <button type="button" onclick="validateNewProjectForm()">Submit</button> -->
								<!-- <button style="display: none" type="button" id="modalPreview" data-toggle="modal" data-target="#myModal"
									onclick="checkAndOpenModal()">Submit</button> -->
								<button type="button" class="btn uBtn mb-2" id="modalPreview"
									onclick="checkAndOpenModal()">Submit
                                </button>
							</h4>
						</section>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog modal-lg modalBlur">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#ccc">
				<button type="button" class="close" data-dismiss="modal">&times;</button>

			</div>
			<div class="modal-body container" data-spy="scroll"
				style="position:relative; text-align:justify; text-justify:auto;">
				<h6><b>Project Name</b></h6>
				<p id="titleM"></p>
				<h6><b>Category</b></h6>
				<p id="catM"></p>
				<h6><b>Thumbnail Image</b></h6>
				<img id="thumbnail_imageM" style="width: 100%"/>
				<h6 class="mt-4"><b>Target Amount</b></h6>
				<p id="budgetM"></p>
				<h6><b>Reward</b></h6>
				<p id="rewardM"></p>
				<h6><b>Project Summary</b></h6>
				<p id="summaryM"></p>
				<h6><b>Project Purpose Image</b></h6>
				<img id="service_imageM" style="width: 100%"/>
				<h6 class="mt-4"><b>Project Details</b></h6>
				<p id="detailM"></p>
				<div id="detailMDiv">
					<h6><b>Project Details Image</b></h6>
					<img id="detail_imageM" style="width: 100%"/>
				</div>
				<div id="breakdownMdiv">
					<h6 class="mt-4" id="breakdownH"><b>Budget Breakdown</b></h6>
					<p id="breakdownM"></p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn uBtn" data-dismiss="modal">Edit</button>
					<button type="button" class="btn uBtn" onclick="sumbitForm()">Confirm</button>
				</div>
			</div>
		</div>
	</div>
</div>



@stop

@section('custom_js')

<script>
	function checkAndOpenModal(e) {
        // $('#myModal').modal('show');
        console.log("Can u see me?");
		divhide();
		// $(document).on('click', '#modalPreview', function (e) {
			// e.preventDefault();
			var validated = validateNewProjectForm();
			if (validated) {
				// console.log("Hello");
				$('#myModal').modal('show');
			}
		// });
	}
</script>

<!-- <script src="//cdn.ckeditor.com/4.8.0/basic/ckeditor.js"></script> -->
<script src="{{Request::root()}}/ckeditor/ckeditor.js"></script>
{{-- Amount Fileld. Validate for only number input  --}}
<script>
	$('#price').keypress(function (event) {
		if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
			event.preventDefault(); //stop character from entering input
		}
	});
</script>

<script>
	function divhide() {
		var hide = document.getElementById("breakdownMdiv");
		var hide2 = document.getElementById("detailMDiv");
		var textarea = document.getElementById("additional_details");
		var breakdownImage = document.getElementById("detailfile1");
		if (textarea.value == "") {
			hide.style.display = "none";
		} else {
			hide.style.display = "block";
		}
		if (breakdownImage.value == "") {
			hide2.style.display = "none";
		} else {
			hide2.style.display = "block";
		}
	}
</script>

<script type="text/javascript">
	$('#title1').on('input', function () {
		var title = $("#title1").val();
		$("#titleM").html('<p>' + title + '</p>');
	});

	function selectCategory(sel) {
		var category = sel.options[sel.selectedIndex].text;
		$("#catM").html('<p>' + category + '</p>');
	}
	function selectReward(sel1) {
		var reward = sel1.options[sel1.selectedIndex].text;
		$("#rewardM").html('<p>' + reward + '</p>');
	}
	$('#price').on('input', function () {
		var price = $("#price").val();
		$("#budgetM").html('<p>' + price + ' BDT' + '</p>');
	});


	$('#summary').on('input', function () {
		var summary = $("#summary").val();
		$("#summaryM").html('<p>' + summary + '</p>');
	});

	$('#service_details').on('input', function () {
		var detail = $("#service_details").val();
		$("#detailM").html('<p>' + detail + '</p>');
	});
	$('#additional_details').on('input', function () {
		var breakdown = $("#additional_details").val();
		$("#breakdownM").html('<p>' + breakdown + '</p>');
	});
</script>

<script>
	function showModalFeaturedImage(src, target) {
		var fr = new FileReader();
		// when image is loaded, set the src of the image where you want to display it
		fr.onload = function (e) {
			target.src = this.result;
		};
		src.addEventListener("change", function () {
			// fill fr with image data
			fr.readAsDataURL(src.files[0]);
		});
	}

	var src = document.getElementById("file1");
	var target = document.getElementById("thumbnail_imageM");
	showModalFeaturedImage(src, target);
</script>

<script>
	function showModalPurposeImage(src, target) {
		var fr = new FileReader();
		// when image is loaded, set the src of the image where you want to display it
		fr.onload = function (e) {
			target.src = this.result;
		};
		src.addEventListener("change", function () {
			// fill fr with image data
			fr.readAsDataURL(src.files[0]);
		});
	}

	var src = document.getElementById("purposefile1");
	var target = document.getElementById("service_imageM");
	showModalPurposeImage(src, target);
</script>

<script>
	function showModalDetailImage(src, target) {
		var fr = new FileReader();
		// when image is loaded, set the src of the image where you want to display it
		fr.onload = function (e) {
			target.src = this.result;
		};
		src.addEventListener("change", function () {
			// fill fr with image data
			fr.readAsDataURL(src.files[0]);
		});
	}

	var src = document.getElementById("detailfile1");
	var target = document.getElementById("detail_imageM");
	showModalDetailImage(src, target);
</script>



<!-- Next Date Calculator -->
<script>
	function calculateNextDate() {

		var startDate = document.getElementById('start').value;
		var day = document.getElementById('totalDay').value;
		var endDate = document.getElementById('end').value;
		var sDate = new Date(startDate);
		sDate.setDate(sDate.getDate() + parseInt(day));
		var month = "0" + (sDate.getMonth() + 1);
		var date = "0" + (sDate.getDate() - 1);
		month = month.slice(-2);
		date = date.slice(-2);
		var date = sDate.getFullYear() + "-" + month + "-" + date;
		document.getElementById('end').value = date;
		var end = $("#end").val();
		$("#endM").html('<p>' + end + '</p>');
	}
</script>

<script>
	// Defining a function to display error message
	function printError(elemId, hintMsg) {
		document.getElementById(elemId).innerHTML = hintMsg;
	}

	// Defining a function to validate form
	function validateNewProjectForm() {

		// Retrieving the values of form elements
		var title = document.addNewServiceForm.title.value;
		var category = document.addNewServiceForm.category.value;
		var thumbnail_image = document.addNewServiceForm.thumbnail_image.value;
		var price = document.addNewServiceForm.price.value;
		var reward = document.addNewServiceForm.reward.value;

		var summary = document.addNewServiceForm.summary.value;
		var service_image = document.addNewServiceForm.service_image.value;
		var service_details = document.addNewServiceForm.service_details.value;
		// var additional_details_image = document.addNewServiceForm.additional_details_image.value;
		// var additional_details = document.addNewServiceForm.additional_details.value;



		// Defining error variables with a default value
		var titleErr = categoryErr = thumbnail_imageErr = priceErr = fromErr = total_dayErr = summaryErr = rewardErr =
        service_imageErr = service_detailsErr = true;
		//Validate title
		if (title == "") {
			printError("titleErr", "Please enter a title");
		} else {
			printError("titleErr", "");
			titleErr = false;
		}

		// Validate Category
		if (category == "Select") {
			printError("categoryErr", "Please select your category");
		} else {
			printError("categoryErr", "");
			categoryErr = false;
		}
		// Validate Reward
		if (reward == "Select") {
			printError("rewardErr", "Please select your category");
		} else {
			printError("rewardErr", "");
			rewardErr = false;
		}

		// Validate Featured image
		if (thumbnail_image == "") {
			printError("thumbnail_imageErr", "Please upload your feature image");
		} else {
			printError("thumbnail_imageErr", "");
			thumbnail_imageErr = false;
		}

		// Validate budget
		if (price == "") {
			printError("priceErr", "Please enter your price");
		} else {
			printError("priceErr", "");
			priceErr = false;
		}

		// // Validate Start date
		// if (from == "") {
		// 	printError("fromErr", "Please select a valid date");
		// } else {
		// 	printError("fromErr", "");
		// 	fromErr = false;
		// }
		// // Validate total days
		// if (total_day == "") {
		// 	printError("total_dayErr", "Please enter total day");
		// } else {
		// 	printError("total_dayErr", "");
		// 	total_dayErr = false;
		// }
		// Validate summary
		if (summary == "") {
			printError("summaryErr", "Please enter a valid summary");
		} else {
			printError("summaryErr", "");
			summaryErr = false;
		}
		// Validate Service image
		if (service_image == "") {
			printError("service_imageErr", "Please upload your valid purpose image");
		} else {
			printError("service_imageErr", "");
			service_imageErr = false;
		}
		// Validate Details
		if (service_details == "") {
			printError("service_detailsErr", "Please enter a valid service_details");
		} else {
			printError("service_detailsErr", "");
			service_detailsErr = false;
		}
		// Validate Details
		// if (additional_details_image == "") {
		// 	printError("detail_imageErr", "Please upload your valid details image");
		// } else {
		// 	printError("detail_imageErr", "");
		// 	detail_imageErr = false;
		// }
		// Validate Details
		// if (additional_details == "") {
		// 	printError("budget_usage_breakdownErr", "Please enter a valid budget breakdown");
		// } else {
		// 	printError("budget_usage_breakdownErr", "");
		// 	budget_usage_breakdownErr = false;
		// }
		// Prevent the form from being submitted if there are any errors
		if ((titleErr || categoryErr || thumbnail_imageErr || priceErr || summaryErr || rewardErr ||
        service_imageErr || service_detailsErr) == true) {

			return false;
		} else {
			// $('#new-service-form').submit();
			return true;
		}
	}
</script>
<script>
	function sumbitForm() {
		$('#new-service-form').submit();
	}
</script>

<script type="text/javascript">
	$(document).on('click', '#upfile1', function () {
		$("#file1").trigger('click');
		$('#file1').change(function () {
			var filename = $('#file1').val();
			$('#select_file').html(filename);
			$('#no_file').html("");
		});
		return false;
	});
</script>

<script type="text/javascript">
	$(document).on('click', '#purposeupfile1', function () {
		$("#purposefile1").trigger('click');
		$('#purposefile1').change(function () {
			var filename = $('#purposefile1').val();
			$('#purpose_select_file').html(filename);
			$('#pupose_no_file').html("");
		});
		return false;

	});
</script>

<script type="text/javascript">
	$(document).on('click', '#detailupfile1', function () {
		$("#detailfile1").trigger('click');
		$('#detailfile1').change(function () {
			var filename = $('#detailfile1').val();
			$('#detail_select_file').html(filename);
			$('#detail_no_file').html("");
		});
		return false;
	});
</script>
@stop
