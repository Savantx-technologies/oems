<x-mail::message>
# New Demo Request Received

A new request for a demo has been submitted through the website.

Here are the details:

- **Name:** {{ $details['name'] }}
- **Email:** [{{ $details['email'] }}](mailto:{{ $details['email'] }})
- **Institution:** {{ $details['institution'] }}
@if(!empty($details['phone']))
- **Phone:** {{ $details['phone'] }}
@endif
@if(!empty($details['role']))
- **Role:** {{ $details['role'] }}
@endif

@if(!empty($details['message']))
## Message
<x-mail::panel>
{{ $details['message'] }}
</x-mail::panel>
@endif


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
