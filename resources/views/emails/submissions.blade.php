@component('mail::message')
Hello {{Session::get('userData')->first_name}},

<p>
    Your submission for {{Session::get('userData')->divisions_label}} division has been received successfully.
</p>

<p>
    You can <a href="{{config('app.url')}}/{{Str::slug(Session::get('userData')->divisions_label, '-')}}/{{Session::get('userData')->division_id}}">click here</a> to view submissions for your division.
</p>

<p>Thank you,</p>
<p>{{ config('app.name') }}</p>
@endcomponent