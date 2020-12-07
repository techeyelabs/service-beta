@if (Session::has('timeout_message'))
<div class="alert alert-info m-t-sm">{!! Session::get('timeout_message') !!}</div>
@endif
@if (Session::has('message'))
<div class="alert alert-info m-t-sm">{!! Session::get('message') !!}</div>
@endif
@if (Session::has('error_message'))
<div class="alert alert-danger m-t-sm">{!! Session::get('error_message') !!}</div>
@endif
@if (Session::has('warning_message'))
<div class="alert alert-warning m-t-sm">{!! Session::get('warning_message') !!}</div>
@endif
@if (Session::has('success_message'))
<div class="alert alert-success m-t-sm">{!! Session::get('success_message') !!}</div>
@endif