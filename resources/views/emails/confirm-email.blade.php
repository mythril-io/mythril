@component('mail::message')
# One Last Step

Thank you for creating an account with us!
We just need you to confirm your email address to prove that you're a human.

@component('mail::button', ['url' => url('/user/verify/' . $verification_code)])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
