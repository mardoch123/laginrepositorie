@component('mail::message')
# Rappel de traitements à effectuer

Bonjour {{ $user->name }},

Vous avez **{{ $count }} traitement(s)** à effectuer dans les **3 prochaines heures**.

@component('mail::table')
| Lapin | Médicament | Heure prévue |
| ----- | ---------- | ------------ |
@foreach ($treatments as $treatment)
| {{ $treatment->rabbit->name }} | {{ $treatment->medication->name }} | {{ $treatment->scheduled_at->format('H:i') }} |
@endforeach
@endcomponent

@component('mail::button', ['url' => route('treatments.upcoming')])
Voir les traitements à effectuer
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent