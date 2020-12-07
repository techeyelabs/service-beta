<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<style>
    .main_div{
        text-align: center;
        border: 1px solid #ececec;
        background-color: #ececec;
    }

    .inner_child{
        display: inline-block;
        text-align: left;
        margin-bottom: 50px;
        border: 1px solid white;
        background-color: white;
        padding: 5%;
        border-top: 4px solid #6daece;
        box-shadow: 1px 1px 15px 1px grey;
    }
</style>
@yield('custom_css')

<head>
    
</head>
    <body>
        <div style="text-align: center; border: 1px solid #ececec; background-color: #ececec;">
            <div class="">  
                <div style="text-align: center; margin-top: 50px; margin-bottom: 30px">
                    <img style="height: 30px" src="{{Request::root()}}/assets/systemimg/logo.png" />
                </div> 
                <div style="display: inline-block; text-align: left; margin-bottom: 50px; border: 1px solid white; background-color: white; padding: 5%; border-top: 6px solid #6daece; box-shadow: 1px 1px 15px 1px grey; width: 400px">
                    @yield('content')
                </div> 
                <div>
                    <footer style="text-align: center;">
                        <p class="copyright_area">&copy; {{date('Y')}} crowd-village.com</p>
                    </footer>
                </div>
            </div>
        </div>      
        @yield('custom_js')
    </body>
</html>
