<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://js.pusher.com/4.4/pusher.min.js"></script>
 
</head>
<body>
  <h1>Pusher Test</h1>
  <button id="rtm_btn">Real Time Notification send</button>
   <script>
	$(function(){
		$("#rtm_btn").click(function(){

      for(var i=0;i<=10;i++) {
        $.get("http://localhost/affec/my-event", function(dt){
            console.log(dt);
        });
      }
			
			return false;
		});
	});
  </script>
  <script>

    // Enable pusher logging - don't include this in production
   // Pusher.logToConsole = true;

    var pusher = new Pusher('9e7895c5459f44daad6a', {
      cluster: 'ap2',
      encrypted: true
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      console.log("test:  "+data);	  
	  //alert(JSON.stringify(data));
    });
  </script>
</body>