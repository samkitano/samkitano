@component('mail::message')
# Welcome, {{ $user }}!

In order to complete your registration, please copy the code below, and paste it into your authentication form.

@component('mail::panel', ['url' => ''])
{{ $token }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
