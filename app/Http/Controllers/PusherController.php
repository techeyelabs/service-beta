<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
 
class PusherController extends Controller
{
	
	public function index()
	{
		return view("real_time_notification");
	}
	
    public function sendNotification()
	{
		//Remember to change this with your cluster name.
        $options = array(
            'cluster' => 'ap2', 
            'encrypted' => true
        );

       //Remember to set your credentials below.
        $pusher = new Pusher(
            '9e7895c5459f44daad6a',  //key
            '2652c1c5f451331b99c9', //secret
            '814962', //app_id 
            $options
        );
        


        $message = "Hello User this is sample realtime notification with laravel 5.4 = ".time();


        $pusher->trigger('my-channel' , 'my-event' , $message );
	}
}



/*
https://www.kerneldev.com/2018/01/21/real-time-notifications-in-laravel-using-pusher/
*/
