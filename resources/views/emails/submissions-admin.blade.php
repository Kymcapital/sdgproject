@component('mail::message')
Hello {{$firstName}},

<p>
    A new review has been submitted.
</p>

<p>
    Login <a href="{{config('app.url')}}/responses">here</a> to approve it.
</p>

<p>Thank you,</p>
<p>{{ config('app.name') }}</p>
@endcomponent