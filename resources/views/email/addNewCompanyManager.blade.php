@component('mail::message')
# 

Sei appena stato invitato a registrarti come {{ $role }} presso l'azienda {{ $enterprise}}

@component('mail::button', ['url' => $url])
Registrati
@endcomponent


Se non visualizzi il bottone correttamente copia e incolla questo link in un browser {{$url}}
<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
