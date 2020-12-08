@extends('systems.auth')

@section('content')

<div class="">
    <div>
        @include('systems.entryheader')
    </div>

    <section class="auth_form_area pt-5 pb-5">
        <div class="col-md-12 alternates" style="max-width: 500px !important; min-height: 500px">
            <div class="col-sm-12 bg-white area_auth">
                <div class="col-md-12 col-sm-12 part_1" >
                    @include('systems.message')
                    <form id="login" class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">E-mail</label>

                            <div>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>


                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">Password</label>

                            <div>
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>

                                    </span>
                                @endif
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                            </div>
                        </div>

                        {{--<div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> &nbsp;&nbsp; 次回から自動的にログイン
                                </label>
                            </div>
                        </div>--}}

                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <button id="submitbtn" type="submit" class="extra_banner_top btn uBtn" style="width: 200px; height: 40px; font-size: 17px" onclick="disable()">
                                    Login
                                </button>
                            </div>
                            <div class="col-md-12 text-center">

                                <a class="btn btn-link" href="#" style="padding-bottom: 0px; margin-top: 20px; font-size: 15px">
                                    Forgot password
                                </a>
                            </div>
                            <div class="col-md-12 text-center">
                                <span style="font-size: 15px !important">To register, click <a class="" href="{{ route('user-register-request') }}">here</a></span>
                            </div>
                            <!-- <div class="col-md-12 text-center">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit
                            </div> -->
                        </div>
                    </form>
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
        $(document).ready(function () {

        });
        function disable(){
            $("#submitbtn").attr("disabled", true);
            $("#login").submit();
        }
    </script>
@endsection
