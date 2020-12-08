@extends('authentication.email.template')

@section('content')
    <div style="width: 100%; text-align: left; margin-top: 10px; margin-bottom: 20px; color: black">
        <span style="font-size: 18px"><b>Hi</b></span><br/><br/>
        <span style="font-size: 15px">To complete the sign up process please verify your email by clicking the following button</span><br/>
    </div>
    <div style="width: 100%; text-align: center; padding: 20px; margin-top: 40px; margin-bottom: 40px">
       <a href="<?php echo $data['root']; ?>/register/<?php echo $data['register_token']; ?>"
       style="display: block; width: 115px; height: 40px; background: #4E9CAF; padding: 10px; text-align: center; border-radius: 5px; color: white;font-weight: bold;line-height: 25px;margin-left: 25%;
   ">

           Verify Email
        </form>
        </a>
    </div>
    <hr/>
    <div style="margin-top: 40px">
        <span>You can also copy and paste the following URL in the browser</span><br/>
        URL：<a href="<?php echo $data['root']; ?>/register/<?php echo $data['register_token']; ?>"><?php echo $data['root']; ?>/register/<?php echo $data['register_token']; ?></a><br/>
        <div style="margin-top: 30px">
            <div><span style="font-size: 17px"><b>Cheers</b></span></div><br/>
            <div><span style="font-size: 17px"><b>Crowd Village Team</b></span></div><br/>
        </div>
    </div>


@endsection
