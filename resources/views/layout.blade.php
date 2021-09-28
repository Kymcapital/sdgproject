<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>{{env('APP_NAME')}} @hasSection('title') | @yield('title')@endif</title>
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

  <link rel="stylesheet" href="{{url('/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{url('/css/jquery.dataTables.min.css')}}">

  <link rel="stylesheet" href="{{url('/css/sweetalert.min.css')}}">
  <link rel="stylesheet" href="{{url('/style.css')}}">
  <link rel="stylesheet" href="{{url('/css/responsive.css')}}">

  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

  <script src="{{url('/js/jquery.js')}}"></script>
  <script src="{{url('/js/jquery.validate.js')}}"></script>
  <script src="{{url('/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{url('/js/bootstrap.min.js')}}"></script>
  <script src="{{url('/js/sweetalert.min.js')}}"></script>

</head>
<body>
  <!-- wrapper -->
  <div class="wrapper">
    <!-- container -->
    <div class="container">
      <!-- MENU -->
      @if (session('accessToken'))
        @include('menu_bar')
      @endif

      <!-- HEADING -->
      @hasSection('title')
        <h2 class="text-center header-one">{{ Str::plural(app()->view->getSections()['title']) }}</h2>
      @endif

      @if(session()->has('userData'))
        Welcome: <strong>{{Session::get('userData')->first_name}}</strong>
        <small class="">({{Session::get('userData')->roles_name}})</small>
        <small class="">({{Session::get('userData')->divisions_label}})</small>
        <hr/>
      @endif

      <!-- ACTION BUTTONS -->
          @hasSection('title')
            <ul aria-label="Company control buttons" class="list-unstyled d-flex">
              @hasSection('activeAddBtn')
                <li><a href="javascript:void(0)" class="btn btn-primary mr-2" id="create-new-{{ Str::of(app()->view->getSections()['title'])->slug('-') }}"><i class="fas fa-plus"></i>&nbsp; Add @yield('title')</a></li>
              @endif
              @hasSection('activeImportBtn')
                <li><a href="javascript:void(0)" class="btn btn-primary" id="import-new-{{ Str::of(Str::plural(app()->view->getSections()['title']))->slug('-') }}"><i class="fas fa-file-import"></i>&nbsp; Import {{ Str::plural(app()->view->getSections()['title']) }}</a></li>
              @endif
            </ul>
          @endif

      <!-- ALERT MESSAGES -->
      @include('alert')

      <!-- CONTENT -->
      @yield('content')
    </div>
    <!-- ./container -->
  </div>
  <!-- ChartJs -->

  <script src="{{url('/js/Chart.min.js')}}"></script>

  <!-- ./wrapper -->
  <script src="{{url('/js/custom.js')}}"></script>
</body>
</html>
