<style>
    .nn a {
        float: left;
        font-size: 16px;
        color: white;
        text-align: center;
        padding: 15px 16px;
        text-decoration: none;
    }

    .dropdown {
        float: left;
        overflow: hidden;
    }

    .dropdown .dropbtn {
        font-size: 16px;
        border: none;
        outline: none;
        color: white;
        padding: 14px 16px;
        background-color: inherit;
        font: inherit;
        margin: 0;
    }

    /* .nn a:hover,
    .dropdown:hover .dropbtn {
        background-color: #ffcccc;
    } */

    .dropdown-content {
        display: none;
        position: fixed;
        background-color: #f9f9f9;
        width: 100%;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 2;
    }

    .dropdown-content .header {
        background: #507998;
        padding: 16px;
        color: white;
    }

    .dropdown-content .header2 {
        background: #507998;
        padding: 16px;
        color: white;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }


    .uBtnbm {
        width: 200px !important;
        height: 50px;
        text-align: center;
        background-color: #FFFFFF;
        color: #000000;
        /* color: #7f9098; */
        border: 1px solid #d2d2d2;
        border-radius: 4px;
        background-color: white;
        padding: 5px;
        box-shadow: 1px 1px 1px 1px #adadad;
    }

    .uBtnbm:hover {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 4px 10px 0 rgba(0, 0, 0, 0.19);
        color: #7f9098;
    }

    .table {
        display: table;
        /* Allow the centering to work */
        margin: 0 auto;
    }

    ul#horizontal-list {
        min-width: 696px;
        list-style: none;
        padding-top: 40px;
    }
    ul#horizontal-list2 {
        min-width: 696px;
        list-style: none;
        padding-top: 10px;
        margin-bottom: -5px;
    }
    ul#horizontal-list3 {
        min-width: 696px;
        list-style: none;
        /* padding-bottom: 0px; */
        margin-bottom: 45px;
    }

    ul#horizontal-list li {
        display: inline;
        cursor: pointer;
    }
    ul#horizontal-list2 li {
        display: inline;
        cursor: pointer;
    }
    ul#horizontal-list3 li {
        display: inline;
        cursor: pointer;
    }
    .tooltipx .tooltiptext::after {

        content: "" !important;
        position: absolute !important;
        bottom: 100% !important;
        left: 89%;
        margin-left: 40px !important;
        border-width: 9px !important;
        border-style: solid !important;
        border-color: transparent transparent #c2c2c2 transparent !important;
    }
</style>


<!-- big header 1 -->
<div class="flex_cont "
    style="height: 75px; border-bottom: 1px solid #dadada; padding-top: 5px; overflow: visible !important;">
    <div class="col-md-12 alternates">
        <table style="width: 100%; height: 70px">
            <tbody>
                <tr style="border-bottom: 1px solid #dadada">
                    <td style="vertical-align: bottom; padding-bottom: 10px" style="width: 33.33%">
                        <a href="#" class="p-1"><img height="55px"
                                src="{{Request::root()}}/assets/systemasset/servicelogo.png" /></a>
                    </td>
                    <td class="nn" style="vertical-align: bottom; width: 33.33%; text-align: center" id="menuBar">
                        <a style="padding-left: 10px; padding-right: 10px; font-size: 25px"><b  style=" background-color:#CCCCCC;  border: 0px solid gray; border-radius: 5px; padding: 5px;">Service</b></a>
                        <a style="padding-left: 10px; padding-right: 10px; font-size: 25px"><b>Shop</b></a>
                        <div class="dropdown " style="">
                            <a href="#" style="padding-left: 10px; padding-right: 5px; font-size: 25px; color:black; cursor:pointer;"
                                class="dropbtn"><b >Fund</b>
                                <div class="dropdown-content" style="margin-top:14px; " id="cont">
                                    <div class="table header">
                                        <ul id="horizontal-list" >
                                            <li
                                                onclick="location.href='#">
                                                Latest Project
                                            </li>
                                            <li
                                                onclick="location.href='#">
                                                / Top Donaton
                                            </li>
                                            <li
                                                onclick="#">
                                                / End Soon
                                            </li>
                                            <li
                                                onclick="#">
                                                / Coming Soon
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="header2" style="height:260px;">
                                        <div class="table">
                                            <ul id="horizontal-list2">
                                                <li
                                                    onclick="location.href='#">
                                                    > Medical, Health & Humanities
                                                </li>
                                                <li
                                                    onclick="location.href='#">
                                                    > Creative & Innovation
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="table">
                                            <ul id="horizontal-list3">
                                                <li
                                                    onclick="location.href='#">
                                                    > Education
                                                </li>
                                                <li
                                                    onclick="location.href='#">
                                                    > Animals
                                                </li>
                                                <li
                                                    onclick="location.href='#">
                                                    > Nature
                                                </li>
                                                <li
                                                    onclick="location.href='#">
                                                    > Emergency
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="">
                                            <button class="extra_banner_top btn uBtnbm text-center mt-4"
                                                onclick="location.href='#"
                                                style="font-size: 25px !important;">
                                                <b>Start Raising</b>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </a>
    </div>

    <a style="padding-left: 0px; padding-right: 10px; font-size: 25px"><b>Talk</b></a>
    </td>
    <td style="width: 33.33%; vertical-align: bottom; text-align: right">
        {{--<div id="right" class="pt-4">Login/Registration</div>--}}
        @if(Auth::user())
        <li class="list-inline-item"><a href="{{ route('user-logout') }}"  class="login_reg_buttons">Logout <b>/</b></a></li>
        <li class="list-inline-item" style="margin-top: -11px !important;">
            <a class="" style="">
                <span id="large_menu" style="border: none" class="tooltipx">
                    <div class="pp">
                    <img class="" id="mymenu" onclick="show()" class="" style="border-radius: 50%; width: 40px"
                        src="{{Request::root().'/uploads/'.Auth::user()->pic}}" alt="...">
                        <!-- <span class="ppbadge" id="ppbadge"></span> -->
                    </div>
                    <div class="tooltiptext" style="width: 180px; text-align: left; margin-left: -100px !important; z-index: 1 !important;">
                        <div class="bottomline" style="padding-bottom: 0px !important; font-size: 14px" id="my-name">
                            {{Auth::user()->first_name.' '.Auth::user()->last_name}}</div>
                        <div class="bottomline pb-3" style="border-bottom: 1px solid #ccc; padding-top: 0px !important"
                            id="my-page">My Page</div>
                        <div class="bottomline pt-3" id="projectsupport">Contributed Projects</div>
                        <div class="bottomline" id="projectdrafts">My Projects</div>
                        <div class="bottomline pb-3" style="border-bottom: 1px solid #ccc">
                            <p id="projectUpload" class="mb-0">Start Raising</p>
                        </div>


                                        <div class="bottomline notification mt-2 mb-1" id="notification">

                                            <span class="mb-0" style="padding-bottom: 0px !important; padding-top:4px !important;">	Notifications </span>
                                            <!-- <span class="badge" id="badgecount">3</span> -->
                                        </div>


                        <div class="bottomline pt-3 pb-3" style="border-bottom: 1px solid #ccc; padding-top: 0px !important;" id="mymessage">Message
                        </div>


                        <div class="bottomline pt-3" id="logout">LogOut</div>
                    </div>
                </span>
            </a>
        </li>
        @else
        <li class="list-inline-item"><a href="{{ route('login') }}"  class="login_reg_buttons">Login <b>/</b></a></li>
        <li class="list-inline-item"><a href="{{ route('user-logout') }}"
                class="login_reg_buttons">Logout</a></li>
        @endif
    </td>
    </tr>
    </tbody>
    </table>
</div>
</div>

<!-- Second Nav for Mobile View -->
<div class="flex_cont" id="nav2"
    style="height: 75px; border-bottom: 1px solid #dadada; padding-top: 5px; overflow: visible !important; display:none; ">
    <div class="col-md-12 alternates" style="text-align: center">
        <table style="width: 100%; height: 70px; margin:0 auto; ">
            <tbody>
                <tr style="border-bottom: 1px solid #dadada">
                    <td style="vertical-align: bottom;  text-align: center" id="menuBar2">
                        <a style=""><img id="service" style="border-radius: 50%; width: 50px"
                                src="{{Request::root().'/uploads/subsystems_icons/menu1.jpg'}}" alt="..."></a>&nbsp;
                        &nbsp;
                        <a style=""><img id="shop" style="border-radius: 50%; width: 50px"
                                src="{{Request::root().'/uploads/subsystems_icons/menu1.jpg'}}" alt="..."></a>&nbsp;
                        &nbsp;
                        <a id="project_small" class="text-center" style=" "><img id="fund" style=""
                                src="{{Request::root().'/uploads/subsystems_icons/menu1.jpg'}}" alt="..."></a>&nbsp;
                        &nbsp;
                        <a style=""><img id="talk" style="border-radius: 50%; width: 50px"
                                src="{{Request::root().'/uploads/subsystems_icons/menu1.jpg'}}" alt="..."></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>




<!-- big header 2 -->

<div style="height: 85px;">
    <div class="col-md-12 alternates">
        <table style="width: 100%; height: 85px">
            <tbody>
                <tr>
                    <td style="vertical-align: middle" style="width: 20%"></td>
                    <td style="width: 60%; text-align: center; padding-top: 30px !important; padding-bottom: 25px;"
                        id="searchBox">
                        <form id="search_form" action="#" method="get" autocomplete="off" style="width: 100%; ">
                            <div class="input-group input_search" style="width: 100%; z-index: 1;">
                                <input style="border-radius: 20px" type="text" class="form-control search"
                                    placeholder="Search" aria-describedby="basic-addon2" name="search" id="search"
                                    value="{{Request::get('title')}}">
                                <input type="hidden" name="category_id" id="category_id" value="" >
                            </div>
                        </form>
                    </td>
                    <td style="width: 20%"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>






<!-- Big menu showup for projects, you can go anywhere from here   -->
<div class="collapse navbar-collapse flex_cont" id="navbarSupportedContent_project"
    style="height: auto; background-color: #1964af; width: 100%; position: absolute; z-index: 999; margin-top: -85px">
    <div class="text-center small_screen_drowpdownpads"
        style="width: 100%; padding-left:15%; padding-right: 15%; padding-top: 4%; color: white; font-size: 16px">
        <span>
            <a href="#" style="color: white">新着プロジェクト<span
                    style="padding-left: 24px; padding-right: 20px">/</span></a>
            <a href="#" style="color: white">支援総額プロジェクト<span
                    style="padding-left: 24px; padding-right: 20px">/</span></a>
            <a href="#" style="color: white">終了間近プロジェクト<span
                    style="padding-left: 24px; padding-right: 20px">/</span></a>
            <a href="#" style="color: white">もうすぐ公開プロジェクト<span
                    style="padding-left: 24px; padding-right: 20px"></span></a>
        </span>
    </div>
    <div id="project_cats" class="text-center row small_screen_drowpdownpads"
        style="height: auto; width: 100%; padding-left:15%; padding-right: 15%; padding-bottom: 4%; padding-top: 2%; color: white; font-size: 12px">

    </div>
</div>



<!-- Big menu for project ends -->

<script>

    var prevScrollpos = window.pageYOffset;
    window.onscroll = function () {
        var currentScrollPos = window.pageYOffset;
        if (prevScrollpos > currentScrollPos) {
            document.getElementById("cont").style.top = "60px";
        } else {
            document.getElementById("cont").style.top = "-350px";
        }
        prevScrollpos = currentScrollPos;
    }

function notificationCount() {
    var ajaxurl = "#";
    $.ajax({
        url: ajaxurl,
        type: "GET",
        success: function(data){
            $('#badgecount').html(data);
        },
    });
}

function totalNotificationCount() {
    var ajaxurl = "#";
    $.ajax({
        url: ajaxurl,
        type: "GET",
        success: function(data){
            $('#ppbadge').html(data);
        },
    });
}

$(document).ready(function () {
    notificationCount();
    totalNotificationCount();
});



</script>
