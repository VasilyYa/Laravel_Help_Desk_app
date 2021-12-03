@component('mail::message')

Здравствуйте, {{ $user->full_name }} !

Статус заявки № {{ $issue->id }} изменился на "{{ $issue->status->description }}".

@component('mail::button', ['url' => route('issuesShow', ['issue' => $issue->id])])
Посмотреть заявку
@endcomponent

Спасибо,<br>
{{ config('app.name') }}
@endcomponent
