<!DOCTYPE html>
<html lang="en" style="margin-right: 0px">

<head>
    <title>Service</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Twitter Card data -->
    <meta name="twitter:card" value="summary">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{isset($social_title)?$social_title:'crowdfunding'}}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:image" content="{{isset($social_image)?$social_image:'crowdfunding'}}" />
    <meta property="og:description" content="{{isset($social_description)?$social_description:'crowdfunding'}}" />
    <!-- working dropdown -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
        integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{Request::root()}}/assets/style.css">
    <link rel="stylesheet" type="text/css"
        href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="{{Request::root()}}/jQuery-ScrollTabs-2.0.0/css/scrolltabs.css">

    <link rel="stylesheet" type="text/css" href="{{ Request::root() }}/assets/front/library/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="{{Request::root()}}/assets/front/library/slick/slick-theme.css">
    {{-- <link rel="stylesheet" href="{{ asset('js-socials/jssocials.css') }}">
    <link rel="stylesheet" href="{{ asset('js-socials/jssocials-theme-flat.css') }}"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{Request::root()}}/assets/js_socials/jssocials.css"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{Request::root()}}/assets/js_socials/jssocials-theme-flat.css">
    --}}
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>
    <script type="text/javascript" src="{{Request::root()}}/assets/front/library/slick/slick.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
        integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
        integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js">
    </script>
    <script src="https://unpkg.com/vue"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.3.4/vue-resource.min.js">
    </script>
    <script type="text/javascript">
        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
        var english = /^[A-Za-z0-9]*$/;

    </script>



    @yield('custom_css')
</head>

<body>

    <div style="position: relative; z-index: 100">
        @include('systems.navbar')
    </div>

    <div class="front" style="position: relative; z-index: 1">
        <div id="body" style="font-family: 'Goudy Old Style' !important; min-height: 700px !important">
            @yield('content')
        </div>

        <div>
            @include('systems.footer')
        </div>
    </div>
</body>

</html>



@yield('custom_js')
<script type="text/javascript">

</script>
