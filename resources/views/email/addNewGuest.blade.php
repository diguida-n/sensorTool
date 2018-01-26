@component('mail::message')
# 

Sei appena stato invitato a registrarti come {{ $role }} presso l'azienda {{ $enterprise}} per 
visualizzare i dati del sito {{$site}}, una volta registrato potrai accedere ai dati sia da piattaforma sia tramite
API tramite richiesta POST alla rotta {{route('api.transmitSensorData')}} e fornendo come parametri email e password

@component('mail::button', ['url' => $url])
Registrati
@endcomponent


Se non visualizzi il bottone correttamente copia e incolla questo link in un browser {{$url}}
<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
