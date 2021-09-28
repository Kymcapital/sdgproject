@extends('frontend.index')
@section('title', 'Home')

@section('content')

<section>
      
    <div class="flex-center position-ref full-height">
        <div class="text-center">
            <figure class="figure text-center d-block">
                <img src="{{asset('images/o3-logo.png')}}" class="figure-img img-fluid rounded" alt="KCB">
                <figcaption class="figure-caption text-xs-right">
                    <strong>To Access the system, enter your email</strong>
                    <p>Check your email for your SDG tracker link after submitting.</p>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </figcaption>
            </figure>
            <form action="{{route('accesslink')}}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-0" placeholder="Enter your e-mail address" aria-label="Enter your e-mail address" aria-describedby="button-addon2">
                    <button class="btn btn-primary rounded-0" type="submit" id="button-addon2">Send Link</button>
                </div>
                <div class="text-danger mt-3">{{ $errors->has('email') ?  $errors->first('email') : '' }}</div>
            </form>
      
            <!-- ALERT MESSAGES -->
            @include('alert')
        </div>

    </div>

</section>

@endsection
