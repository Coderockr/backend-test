@component('mail::message')
# Withdrawal concluded

If you don't know why you are receiving this email, please contact us.

@component('mail::button', ['url' => ''])
    Contact Us
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
