@extends('systems.auth')

@section('content')
<style type="text/css">
    .norightborder:after{
        display: none !important;
    }
</style>
<div class="auth_area">
    <header class="front_header">
        <div class="container">
            <div class="row">
                <div class="col-10 offset-md-1">
                    <a href="" class="logo_area pt-3"><img height="25px" src="{{Request::root()}}/assets/front/img/logo.png"></a>
                </div>
            </div>
        </div>
    </header>

    <section class="auth_page_title">
        <div class="container">
            <div class="row">
                <div class="col-10 offset-md-1">
                    <h1><i class="fa fa-lock" aria-hidden="true"></i>Pasoward reset</h1>
                </div>
            </div>
        </div>
    </section>



    <section class="auth_form_area">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 col-sm-12 bg-white area_auth">
                    <div class="row">
                        <div class="col-md-8 offset-md-2 col-sm-12 part_1 norightborder">
                            <h2>パソワード再設定</h2>

                                <div class="alert alert-success">

                                </div>


                            @include('systems.message')

                            <form class="form-horizontal" method="POST" action="">
                                {{ csrf_field() }}

                                <input type="hidden" name="token" value="">

                                <div class="form-group">
                                    <label for="email" class="control-label">Registered e-mail address</label>

                                    <div class="">
                                        <input id="email" type="email" placeholder="Registered e-mail address" class="form-control" name="email" value="" required autofocus>


                                            <span class="help-block text-danger">
                                                <strong></strong>
                                            </span>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="control-label">New password</label>

                                    <div class="">
                                        <input id="password" type="password" class="form-control" name="password" placeholder="New password" required>


                                            <span class="help-block text-danger">
                                                <strong></strong>
                                            </span>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password-confirm" class="control-label">新しいパスワード確認</label>
                                    <div class="">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="新しいパスワード確認" required>


                                            <span class="help-block text-danger">
                                                <strong></strong>
                                            </span>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="">
                                        <button type="submit" class="btn btn-primary">
                                            更新する
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
