@component('mail::message')
New email from website!
<br>
<br>
<p>Name: {{ $name }}</p>
<p>Email: {{ $email }}</p>
<p>Message: {{ $message }}</p>

@component('mail::button', ['url' => 'https://dragocode.net/Bet2Gain'])
Visit Website
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
