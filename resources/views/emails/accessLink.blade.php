@component('mail::message')
# Hello,

<p>To access the KCB - SDG Tracker <a href="{{$url}}" target="blank">click here</a>.</p>

<p>This link is valid for only 2 hours.</p>

<p>Thank you,</p>
<p>{{ config('app.name') }}</p>
@endcomponent