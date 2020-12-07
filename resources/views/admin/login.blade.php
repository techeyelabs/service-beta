@extends('admin.layouts.auth')

@section('custom_css')
    
@endsection

@section('content')

<div class="login-box">
    <div class="logo">
        <a href="javascript:void(0);">A<b>EC</b></a>
        <small>Affiliate EC Admin</small>
    </div>
    <div class="card">
        <div class="body">
            <form id="sign_in" method="POST" action="">
                {{ csrf_field() }}
                <div class="msg">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">person</i>
                    </span>
                    <div class="form-line">

                        <input type="email" class="form-control" name="email" placeholder="Email"
                            data-validation="required" data-validation-error-msg="The Email field is required">


                    </div>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input type="password" class="form-control" name="password" placeholder="Password"
                            data-validation="required" data-validation-error-msg="The Password field is required">
                    </div>
                </div>
                <div class="row">

                    <div class="col-xs-8 p-t-5">
                        <!--  <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>-->
                    </div>
                    <div class="col-xs-4">
                        <button class="btn btn-block bg-success waves-effect" type="submit">SIGN IN</button>
                    </div>
                </div>
                <div class="row m-t-15 m-b--20">
                    <!--<div class="col-xs-6">
                            <a href="sign-up.html">Register Now!</a>
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="forgot-password.html">Forgot Password?</a>
                        </div>-->
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('custom_js')
    
@endsection
