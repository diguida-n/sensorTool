@component('mail::message')
# 

Richiesta di informazioni da:<br>
email		: {{$email}}<br>
nome		: {{$name}}<br>
telefono	: {{$phone}}<br>
messaggio	: {{$message}}<br>


@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
