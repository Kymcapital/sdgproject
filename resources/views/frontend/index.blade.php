<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Primary Font -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


        <!-- Bootstrap CSS -->
        <link href="{{ url('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" >

        <!-- Font Awesome CSS -->
        <link href="{{ url('/css/all.css') }}" rel="stylesheet" type="text/css" >
        <link href="{{ url('/css/brands.min.css') }}" rel="stylesheet" type="text/css" >

        <!-- Owl carousel CSS -->
        <link href="{{ url('/css/owl.carousel.min.css') }}" rel="stylesheet" type="text/css" >
        <link href="{{ url('/css/owl.theme.default.min.css') }}" rel="stylesheet" type="text/css" >

        <!-- Main Frontend CSS -->
        <link href="{{ url('/css/frontend-style.css') }}" rel="stylesheet" type="text/css" >
        <link href="{{ url('/css/form-wizard.css') }}" rel="stylesheet" type="text/css" >


        <!-- Responsive CSS -->
        <link href="{{ url('/css/responsive.css') }}" rel="stylesheet" type="text/css" >

        <title>@yield('title') | {{env('APP_NAME')}}</title>
    </head>
    <body>

        @section('header')
            @include('frontend.partials.header')
        @show

        <div class="container-fluid ">
            @yield('content')
        </div>

        @section('footer')
            @include('frontend.partials.footer')
        @show

        <!-- jQuery and Bootstrap Bundle (includes Popper) -->

        <script type="text/javascript" src="{{ url('/js/jquery-3.5.1.slim.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/js/jquery-3.6.0.min.js') }}"></script>

        <script type="text/javascript" src="{{ url('/js/bootstrap.bundle.min.js') }}"></script>

        <!-- ChartJs -->
        <script type="text/javascript" src="{{ url('/js/Chart.min.js') }}"></script>

        <!-- owl carousel -->
        <script type="text/javascript" src="{{ url('/js/owl.carousel.min.js') }}"></script>
        <!-- Custom jQuery -->
        <script type="text/javascript" src="{{ url('/js/custom.js') }}"></script>

     @stack('scripts')
    </body>
</html>
