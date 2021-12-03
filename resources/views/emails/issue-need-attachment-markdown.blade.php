@component('mail::message')

Здравствуйте, {{ $user->full_name }} !

Заявка № {{ $issue->id }} требует прикрепления к менеджеру.

@component('mail::button', ['url' => route('issuesShow', ['issue' => $issue->id])])
Посмотреть заявку
@endcomponent

Спасибо,<br>
{{ config('app.name') }}
@endcomponent
