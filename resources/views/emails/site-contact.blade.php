@component('mail::message')
# Hi, Boss!

A visitor has sent us a message.

## Details
@component('mail::table')
| Key           | Val                       |
| ------------- |---------------------------|
| Name          | {{ $data['name'] }}       |
| Email         | {{ $data['email'] }}      |
| ID            | {{ $data['user_id'] }}    |
| IP            | {{ $data['ip'] }}         |
| UA            | {{ $data['user_agent'] }} |
| DT            | {{ $data['created_at'] }} |
@endcomponent

## Message
@component('mail::panel')
{{ $data['message'] }}
@endcomponent
@endcomponent
