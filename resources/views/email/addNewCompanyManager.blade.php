@component('mail::message')
# 

Sei appena stato invitato a registrarti come Responsabile aziendale presso l'aziend {{ $enterprise->businessName}}

@component('mail::button', ['url' => $url])
Registrati
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
