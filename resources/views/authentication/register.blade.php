@extends('systems.auth')

@section('content')

<div class="">
    <div>
        @include('systems.entryheader')
    </div>

    <section class="auth_form_area pt-5 pb-5">
        <div class="col-md-12 alternates" style="max-width: 500px !important; min-height: 500px">
            <div class="col-sm-12 bg-white area_auth">
                <div class="col-md-12 col-sm-12 part_1">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    @include('systems.message')
                    <form id="second_reg_form" class="form-horizontal" method="POST" action="">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="first_name" class="control-label">First Name</label>

                            <div>
                                <input id="first_name" type="text" class="form-control" name="first_name"
                                    value="{{ old('first_name') }}" maxlength="10" required>

                                @if ($errors->has('first_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label for="last_name" class="control-label">Last Name</label>

                            <div>
                                <input id="last_name" type="text" class="form-control" name="last_name"
                                    value="{{ old('last_name') }}" maxlength="10" required>

                                @if ($errors->has('last_name'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">E.Mail</label>

                            <div>
                                <input id="email" type="email" class="form-control" name="email"
                                    value="{{ $user->email }}" readonly>

                                @if ($errors->has('email'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">Password<a href="#" data-toggle="tooltip"
                                    data-html="true"
                                    title="Password must be at least 8 characters long. <br/> Allowed characters are as follows. A-Z, a-z, #, @, 0-9">?</a></label>

                            <div>
                                <input id="password" type="password" class="form-control" name="password" value=""
                                    required>

                                <span class="help-block text-danger" id="errors_pass"></span>

                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password_confirmation" class="control-label">Confirm Password</label>

                            <div>
                                <input id="password_confirmation" type="password" class="form-control"
                                    name="password_confirmation" value="{{ $user->password_confirmation }}" required>

                                @if ($errors->has('password_confirmation'))
                                <span class="help-block text-danger">

                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                                @endif
                                @if ($errors->has('password'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </form>
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button class="extra_banner_top btn uBtn" id="submit"
                                style="width: 200px; height: 40px; font-size: 17px" onclick="disable()">
                                <b>Submit</b>
                            </button>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $(document).on('click', '#submit', function () {
            function validPassword(password) {
                let errors = '';
                let flag = 0;
                //password < 8
                if (password.length < 8) {
                    errors = "パソワードが８文字以上にする必要です";
                    flag = 1;
                }
                //password has number and chars
                // else if (!password.match(/([a-zA-Z])/)) {
                //         errors = "Passwords must contain at least one letter";
                //         flag = 1;
                // }
                // else if (!password.match(/([0-9])/)) {
                //     errors = "Passwords must contain at least one number";
                //     flag = 1;
                // }
                // else if (!password.match(/([!,@,#,$,%,^,&,*,?,_,~])/)){
                //     errors = "Passwords must contain at least one symbol";
                //     flag = 1;
                // }
                // else if (!password.match(/([A-Z])/)) {
                //     errors = "Passwords must contain at least one capitalized letter";
                //     flag = 1;
                // }
                if (flag == 1) {
                    $('#errors_pass').html(errors);
                    // alert('fdssd');
                    return false;
                }
            }

            var password = $('#password').val();
            validPassword(password);
        });
    });

    // function disable() {
    //     $("#submitbtn").attr("disabled", true);
    //     $("#second_reg_form").submit();
    // }
</script>
    <script>
        function disable() {
            var button = document.getElementById("submit");
            var form = document.getElementById("second_reg_form");
             button.disabled = true;
             form.submit();
        }
    </script>
@endsection
