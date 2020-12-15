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
				<h1 class="text-center page_title_product_register">Service Request</h1>
				<form id="new-request-form" name="addNewRequestForm" action="#" class="mt20"
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
									<label for="purpose"><b>Details</b>
									</label>
									<span class="" style="color:red;">*</span>
								</div>
								<div class="col-md-9">
									<textarea type="text" id="details" class="form-control  col-12" rows="6" cols="40"
										placeholder="" name="details" maxlength="1000"></textarea>
									<div class="error" id="detailsErr"></div>
								</div>
							</div>
							<div class="col-md-12">&nbsp;</div>
						</section>
						<section>
							<h4 class="text-center mt20 mt-5 mb-4">
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
				<h6><b>Title</b></h6>
				<p id="titleM"></p>
				<h6><b>Category</b></h6>
				<p id="catM"></p>
				<h6><b>Details</b></h6>
				<p id="detailsM"></p>
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

			var validated = validateNewRequestForm();
			if (validated) {
				$('#myModal').modal('show');
			}
	}
</script>

<script src="{{Request::root()}}/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
	$('#title1').on('input', function () {
		var title = $("#title1").val();
		$("#titleM").html('<p>' + title + '</p>');
	});

	function selectCategory(sel) {
		var category = sel.options[sel.selectedIndex].text;
		$("#catM").html('<p>' + category + '</p>');
	}
	$('#details').on('input', function () {
		var details = $("#details").val();
		$("#detailsM").html('<p>' + details + '</p>');
	});
</script>



<script>
	// Defining a function to display error message
	function printError(elemId, hintMsg) {
        $('#'+elemId).text(hintMsg)
	}

	// Defining a function to validate form
	function validateNewRequestForm() {

		// Retrieving the values of form elements
		var title = document.addNewRequestForm.title.value;
		var category = document.addNewRequestForm.category.value;
		var details = document.addNewRequestForm.details.value;
		// Defining error variables with a default value
		var titleErr = categoryErr  = detailsErr  = true;
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

		// Validate details
		if (details == "") {
			printError("detailsErr ", "Please enter a valid summary");
		} else {
			printError("detailsErr ", "");
			detailsErr = false;
		}

		// Prevent the form from being submitted if there are any errors
		if ((titleErr || categoryErr || detailsErr ) == true) {

			return false;
		} else {
			// $('#new-request-form').submit();
			return true;
		}
	}
</script>
<script>
	function sumbitForm() {
		$('#new-request-form').submit();
	}
</script>





@stop
