@component('mail::message')
# Thank You, {{ $inquiry->name }}

We have received your inquiry and will get back to you soon.

**Your Message:**
> {{ $inquiry->message }}

If you have more details, feel free to reply to this email.

Thanks,<br>
**Eternal Diamonds Team**
@endcomponent
