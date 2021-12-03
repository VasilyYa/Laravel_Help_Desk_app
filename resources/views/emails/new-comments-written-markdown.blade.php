@component('mail::message')

Здравствуйте, {{ $user->full_name }} !

По заявке № {{ $issue->id }} добавлены новые комментарии.

@component('mail::button', ['url' => route('issuesShow', ['issue' => $issue->id])])
Посмотреть заявку
@endcomponent

Спасибо,<br>
{{ config('app.name') }}
@endcomponent
