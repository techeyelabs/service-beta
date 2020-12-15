@extends('systems.main')
@section('custom_css')
<style type="text/css">
    .pag {
        margin-left: 50%;
        width: 50%;
    }

</style>
@stop
    @section('content')
    <div class="alternates " style="max-width: 900px !important">
        <div class="card shadow-sm  mt-5">
            <div class="card-header">
                <span class="card-title" style="font-size: 18px;"><b>{{ $requestdetails->title }}</b></span>
            </div>
            <div class="card-body" style="">
                <p style="font-size: 16px;" class="card-text">{!!nl2br($requestdetails->content)!!}</p>
                <!-- <a href="#" class="btn uBtn">Go somewhere</a> -->
            </div>
        </div>
    </div>

    @stop

        @section('custom_js')

        <script type="text/javascript">

        </script>

        @stop
