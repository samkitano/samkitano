@component('mail::message')
# Hi, {{ $data['name'] }}!

Just to let you know that your message has been received.

I will get back to you as soon as possible.

Kind regards,

Sam


### Your Message
@component('mail::panel')
{{ $data['message'] }}
@endcomponent
@endcomponent
