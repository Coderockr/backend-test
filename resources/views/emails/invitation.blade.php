@component('mail::message')
# Invitation

You have been invited by {{ $inviter->name }} to join the Coderockr Events platform.

If you are interested, join us by the follow link.

@component('mail::button', ['url' => $url])
Create an account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
