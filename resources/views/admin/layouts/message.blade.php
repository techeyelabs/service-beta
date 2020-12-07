@if (session('message'))
<div class="alert alert-primary alert-dismissible" role="alert">
	{{ session('message') }}
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
@endif
@if (session('secondary_message'))
<div class="alert alert-secondary alert-dismissible" role="alert">
	{{ session('secondary_message') }}
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
@endif
@if (session('success_message'))
<div class="alert alert-success alert-dismissible" role="alert">
  	{{ session('success_message') }}
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
@endif
@if (session('error_message'))
<div class="alert alert-danger alert-dismissible" role="alert">
	{{ session('error_message') }}
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
@endif
@if (session('warning_message'))
<div class="alert alert-warning alert-dismissible" role="alert">
  	{{ session('warning_message') }}
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
@endif
@if (session('info_message'))
<div class="alert alert-info alert-dismissible" role="alert">
  	{{ session('info_message') }}
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
@endif
@if (session('light_message'))
<div class="alert alert-light alert-dismissible" role="alert">
  	{{ session('light_message') }}
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
@endif
@if (session('dark_message'))
<div class="alert alert-dark alert-dismissible" role="alert">
  	{{ session('dark_message') }}
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
@endif