@component('mail::message')
# New Inquiry Received

**Name:** {{ $inquiry->name }}  
**Email:** {{ $inquiry->email }}  
**Message:**
> {{ $inquiry->message }}

[View in Admin Panel]({{ url('/admin/inquiries') }})

@endcomponent
