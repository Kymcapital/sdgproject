

<nav class="navbar navbar-expand-lg navbar-light bg-light p-0">
  <a class="navbar-brand" href="/">
      <img src="{{asset('images/o3-logo.png')}}" class="figure-img img-fluid logo" alt="KCB">
  </a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <!-- Super Admin -->
        <li class="nav-item {{ Route::is('companies.*')  ? 'active' : '' }}" >
          <a class="nav-link" href="{{ route('companies.index')}}">Companies</a>
        </li>
      <!-- /Super Admin -->

      <!-- Admin -->
        <li class="nav-item {{ Route::is('divisions.*')  ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('divisions.index')}}">Division/Department<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item {{ Route::is('gris.*')  ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('gris.index')}}">GRI</a>
        </li>
        <li class="nav-item {{ Route::is('sdg-topics.*')  ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('sdg-topics.index')}}">SDG Topics</a>
        </li>
        <li class="nav-item {{ Route::is('users.*')  ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('users.index')}}">Users</a>
        </li>
        <li class="nav-item {{ Route::is('kpis.*')  ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('kpis.index')}}">KPI's</a>
        </li>

        <li class="nav-item {{ Route::is('review-cycle.*')  ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('review-cycle.index')}}">Review Cycle</a>
        </li>
        
        <li class="nav-item {{ Route::is('responses.*')  ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('responses.index')}}">Responses</a>
        </li>
       

        <!-- /Champion -->
        <li class="nav-item {{ Route::is('faq.*')  ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('faq.admin')}}">FAQ's</a>
        </li>
      <!-- /Admin -->
    </ul>

    <div class="form-inline my-lg-0">
        <a target="_blank" class="btn btn-primary my-2 my-sm-2" href="{{route('switchToFront')}}">Switch To Front</a>
    </div>

    <div class="form-inline my-lg-0">
      <a class="btn btn-outline-primary my-2 my-sm-2" href="{{route('logout')}}">Logout</a>
    </div>
  </div>
</nav>
