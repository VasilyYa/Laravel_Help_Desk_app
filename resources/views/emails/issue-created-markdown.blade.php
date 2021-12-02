@component('mail::message')

Здравствуйте, {{ $user->full_name }}

Создана новая заявка № {{ $issue->id }}.

@component('mail::button', ['url' => route('issuesShow', ['issue' => $issue->id])])
Посмотреть заявку
@endcomponent

Спасибо,<br>
{{ config('app.name') }}
@endcomponent
