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

                    <div class="alert alert-success">

                    </div>

                    @include('systems.message')
                    <form id="second_reg_form" class="form-horizontal" method="POST" action="">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="first_name" class="control-label">First Name</label>

                            <div>
                                <input id="first_name" type="text" class="form-control" name="first_name"
                                    value="" maxlength="10" required>


                                <span class="help-block text-danger">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="last_name" class="control-label">Last Name</label>

                            <div>
                                <input id="last_name" type="text" class="form-control" name="last_name"
                                    value="" maxlength="10" required>


                                <span class="help-block text-danger">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="control-label">E.Mail</label>

                            <div>
                                <input id="email" type="email" class="form-control" name="email"
                                    value="" readonly>


                                <span class="help-block text-danger">
                                    <strong></strong>
                                </span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="control-label">Password<a href="#" data-toggle="tooltip"
                                    data-html="true"
                                    title="Password must be at least 8 characters long. <br/> Allowed characters are as follows. A-Z, a-z, #, @, 0-9">?</a></label>

                            <div>
                                <input id="password" type="password" class="form-control" name="password" value=""
                                    required>

                                <span class="help-block text-danger" id="errors_pass"></span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="control-label">Confirm Password</label>

                            <div>
                                <input id="password_confirmation" type="password" class="form-control"
                                    name="password_confirmation" value="" required>


                                <span class="help-block text-danger">

                                    <strong></strong>
                                </span>

                                <span class="help-block text-danger">
                                    <strong></strong>
                                </span>

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
                {{--<div class="col-12 part_2">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 33.33%; padding-left: 25px; padding-right: 25px"><a href="{{route('front-facebook')}}"
                class="btn btn-primary btn-lg btn-block facebook" style="border-radius: 25px !important;"><i
                    class="fa fa-facebook"></i></a></td>
                <td style="width: 33.33%; padding-left: 25px; padding-right: 25px"><a href="{{route('front-google')}}"
                        class="btn btn-danger btn-lg btn-block google" style="border-radius: 25px !important;"><i
                            class="fa fa-google"></i></a></td>
                <td style="width: 33.33%; padding-left: 25px; padding-right: 25px"><a href="{{route('front-twitter')}}"
                        class="btn btn-info btn-lg btn-block twitter" style="border-radius: 25px !important;"><i
                            class="fa fa-twitter"></i></a></td>
                </tr>
                </table>
            </div>--}}
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
