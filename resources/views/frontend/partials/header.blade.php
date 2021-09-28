

@if(session()->has('userData'))
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <strong>Welcome:</strong> {{Session::get('userData')->first_name}}
                <small class="">({{Session::get('userData')->roles_name}})</small>
                <small class="">({{Session::get('userData')->divisions_label}} Division)</small>
            </div>
            <div class="col-md-6 text-md-right my-md-2">
                <a class="btn btn-primary px-5 text-uppercase" href="{{ route('reviews.index')}}">Add Review @if (Route::is('review.*'))
                @endif</a>

                @if (session('accessToken'))
                    <a class="btn btn-danger px-5" href="{{route('logout')}}">Logout</a>
                @endif
            </div>
        </div>
    </div>
@endif

<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('frontend.overview')}}">
            <img src="{{asset('images/logo-02.jpg')}}" class="img-fluid logo" alt="{{env('APP_NAME')}}">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            @if(isset($divisions) && !empty($divisions))
                <ul class="navbar-nav ml-auto mb-2 mb-lg-0 text-right">

                    <li class="nav-item {{ Route::is('frontend.overview')  ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('frontend.overview')}}">Overview @if (Route::is('frontend.overview' ))
                            <span class="sr-only">(current)</span>
                        @endif</a>
                    </li>
                    @foreach($divisions as $key => $division)
                        @if($key < 7)
                            <li class="nav-item {{ Route::is(''.\Str::slug($division->label, '-').'.*')  ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route(''.\Str::slug($division->label, '-').'.index', ['id'=>$division->id])}}">{{$division->label}} @if (Route::is(''.\Str::slug($division->label, '-').'.*'))
                                    <span class="sr-only">(current)</span>
                                @endif</a>
                            </li>
                        @endif
                    @endforeach

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            More
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @foreach($divisions as $key => $division)
                                @if($key > 6)
                                    <a class="dropdown-item" href="{{ route(''.\Str::slug($division->label, '-').'.index', ['id'=>$division->id])}}">{{$division->label}}</a>
                                @endif
                            @endforeach
                        </div>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>
