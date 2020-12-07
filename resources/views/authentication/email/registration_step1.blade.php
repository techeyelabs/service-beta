@extends('authentication.email.template')

@section('content')
    <div style="width: 100%; text-align: left; margin-top: 10px; margin-bottom: 20px; color: black">
        <span style="font-size: 18px"><b>Hi</b></span><br/><br/>
        <span style="font-size: 15px">To complete the sign up process please verify your email by clicking the following button</span><br/>
    </div>
    <div style="width: 100%; text-align: center; padding: 20px; margin-top: 40px; margin-bottom: 40px">
        <form action="#" method="GET">
            <button style="border-radius: 4px; box-shadow: 2px 1px 3px #0a0a0a; cursor: pointer; background-color: #618ca9; border: 1px solid #618ca9; color: white; height: 45px; width: 175px;">Verify Email</button>
        </form>
    </div>
    <hr/>
    <div style="margin-top: 40px">
        <span>You can also copy and paste the following URL in the browser</span><br/>
        URL：<a href="#">#</a><br/>
        <div style="margin-top: 30px">
            <div><span style="font-size: 17px"><b>Cheers</b></span></div><br/>
            <div><span style="font-size: 17px"><b>Crowd Village Team</b></span></div><br/>
        </div>
    </div>


@endsection
