@extends('systems.auth')

@section('content')
<style type="text/css">
    .norightborder:after{
        display: none !important;
    }
</style>
<div class="">
    <div>
        @include('systems.entryheader')
    </div>


    <section class="auth_form_area pt-5">
        <div class="col-md-12 alternates" style="max-width: 500px !important; min-height: 500px">
            <div class="col-sm-12 bg-white area_auth">
                <div class="col-md-12 col-sm-12 part_1" >

                        <div class="alert alert-success">

                        </div>

                    <form id="pass_reset" class="form-horizontal" method="POST" action="">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="email" class="control-label" style="font-size: 16px">Registered E-mail Address</label>

                            <div>
                                <input id="email" type="email" class="form-control" name="email" value="" required>


                                    <span class="help-block">
                                        <strong></strong>
                                    </span>

                            </div>
                        </div>


                    </form>

                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button id="submitbtn" type="submit" class="extra_banner_top btn uBtn" style="width: 200px; height: 40px; font-size: 17px" onclick="disable()">
                                Submit
                            </button>
                        </div>
                        <div class="col-md-12 text-center">
                            <br>
                            <p>If you have forgotten your password, please enter your email address in the form above and press "Send". The URL for password reset will be sent to the entered email address by email.</p>
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
        $(document).ready(function () {

        });
        function disable(){
            $("#submitbtn").attr("disabled", true);
            $("#pass_reset").submit();
        }
    </script>
@endsection
