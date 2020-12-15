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
<div class="alternates" style="max-width: 900px !important">
    <div class="mt20">
        <div class="row inner flex_cont pl-2">
            @if ($requests)
            @foreach ($requests as $r)

            <div class="notificationBox mb-3 col-md-12 row shadow-sm  bg-white rounded"  style="cursor: pointer;">
                <div class="col-md-8">
                <a href="{{route('my-request-details',['id'=>$r->id])}}">
                    <span style="color:black;">{{$r->title}}</span>
                </a>
                </div>
                <div class="col-md-3">
                    <span style="float:right;">{{$r->created_at}}</span>
                </div>
                <div class="col-md-1">
                  <span class="mt-2" style="float:right;">{{$r->number_of_proposals}}</span>
                </div>
            </div>

            @endforeach
            @endif
        </div>
    </div>
</div>

@stop

@section('custom_js')

<script type="text/javascript">

</script>

@stop
