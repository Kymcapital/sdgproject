@component('mail::message')
# Hello,

<p>A new database backup dated <strong>[{{now()}}]</strong> has been proceed successfully.</p>

<p>Thank you,</p>
<p>{{ config('app.name') }}</p>
@endcomponent