<!doctype html>
<html lang="en">
  <head>
    <title>Funds</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style type="text/css">
      body{
        font-size: 12px;
        background-color: #f9f9f9;
      }
      .btn-no-border-radius{
        border-radius: 0px !important;
      }
      .border-right{
        border-right: 1px solid #ced4da;
      }
      .front_header{
        padding-top: 10px;
        padding-bottom: 10px;
      }
      .margin-top{
        margin-top: 15px;
      }
      .bg-white{
        background-color: #FFF;
        box-shadow: 1px 1px 20px 1px #d4d4d4;
        border-radius: 2px;
      }
      .panel-auth{
        padding: 10px;
      }
      .facebook{
        background-color: #3B5997 !important;
        border-radius: 0px !important;
      }
      .google{
        background-color: #D34735 !important;
        border-radius: 0px !important;
      }
      .twitter{
        background-color: #3884B4 !important;
        border-radius: 0px !important;
      }
      .auth_area{
        background-color: #F1F1F1;
      }
      .front_header{
        background-color: #ffffff;
        padding-top: 20px;
        padding-bottom: 20px;
      }
      .auth_page_title{
        padding-top: 15px;
        padding-bottom: 15px;
      }
      .auth_page_title h1{
        font-size: 20px;
      }
      .auth_area{
        padding-bottom: 100px;
      }
      .auth_form_area .area_auth{
        padding-top: 50px;
      }
      .auth_form_area .area_auth .form-group{
        margin-top: 20px;
      }
      .auth_form_area .part_1 h2, .part_2 h2{
        font-size: 16px;
      }
      .part_2 h2{
        margin-bottom: 30px;
      }
      .part_1, .part_2{
        padding: 40px;
        padding-top: 0px;
      }
      .btn-primary{
        background-color: #618ca9;
        border-color: #618ca9;
      }
      .auth_form_area .part_1{
        position: relative;
      }
      .auth_form_area .part_1:after{
        /* content: "";
        display: block;
        width: 1px;
        height: 100%;
        position: absolute;
        right: 0px;
        top:0px;
        background-color: #C6C6C6; */
      }
      .area_auth{
        padding-bottom: 50px;
      }
      .btn{
        cursor: pointer;
      }
    </style>
    <link rel="stylesheet" type="text/css" href="{{Request::root()}}/assets/style.css">
  </head>
  <body>
    @yield('content')

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
  @yield('custom_js')
</html>
