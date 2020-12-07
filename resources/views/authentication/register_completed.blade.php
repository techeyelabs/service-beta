@extends('systems.auth')

@section('content')

<div class="">
    <div>
        @include('systems.entryheader')
    </div>

    <section class="auth_form_area pt-5 pb-5">
        <div class="col-md-12 alternates" style="max-width: 500px !important; min-height: 500px">
            <div class="col-sm-12 bg-white area_auth text-center font-18">
                <span style="font-size: 18px">Already registered! </span><br />
                <span style="font-size: 16px">Plsase login from <a href="">here</a></span>
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

    function disable() {
        var button = document.getElementById("submitbtn");
        var form = document.getElementById("second_reg_form");
        form.submit();
        button.disabled = true;

    }
</script>
@endsection
