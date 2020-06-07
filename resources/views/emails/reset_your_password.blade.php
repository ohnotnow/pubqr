@component('mail::message')
# You need to reset your password

Your need to reset your password for {{ config('app.name') }}.  Please follow the link below.

@component('mail::button', ['url' => route('password.reset')])
Reset Your Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
