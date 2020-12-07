@extends('systems.auth')

@section('content')

<div class="">
    <div>
        @include('systems.entryheader')
    </div>

    <section class="auth_form_area pt-5">
        <div class="col-md-12 alternates" style="max-width: 500px !important; min-height: 500px">
            <div class="col-sm-12 bg-white area_auth">
                <div class="col-md-12 col-sm-12 part_1">
                    @include('systems.message')

                    <form id="first_reg_form" class="form-horizontal" method="POST" action="">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label" style="font-size: 16px">E.mail Address</label>

                            <div>
                                <input id="email" type="email" class="form-control required" name="email"
                                    value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong> {{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </form>
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            {{-- onclick="disable()" --}}
                            <button id="submitbtn" type="submit" class="extra_banner_top btn uBtn"
                                style="width: 200px; height: 40px; font-size: 17px" onclick=" disable()">
                                Send Mail to Verify
                            </button>
                        </div>
                        <div class="col-md-12" style="margin-top: 20px; text-align: center">
                            <p>※If you have already registered, please sign in <a href="">here</a></p>
                        </div>
                    </div>
                </div>

        </div>
</div>
</section>

<div>
    @include('systems.footer')
</div>
</div>


@endsection

@section('custom_js')
<script type="text/javascript" src="{{Request::root()}}/js/jquery.validate.min.js"></script>

<script>
    function disable() {
        var button = document.getElementById("submitbtn");
        var form = document.getElementById("first_reg_form");
        button.disabled = true;
        form.submit();

    }
</script>

@endsection
