@extends('layouts.main')


@section('title')
    @if(Route::current()->getName() === 'issuesListForUser')
        {{ (auth()->user()->isSeniorManager() || auth()->user()->isAdmin()) ? __('All issues') : __('Current issues') }}
    @elseif(Route::current()->getName() === 'issuesListNotAttached')
        {{ __('Free issues') }}
    @endif
@endsection


@section('content')

    {{--  Header  --}}
    <div class="font-sans">
        <h1 class="font-sans break-normal text-gray-900 pt-0 pb-2 text-xl">@yield('title')</h1>
        <hr class="border-b border-gray-400">
    </div>

    {{--  List all entities  --}}
    @php
        $numberInList = $issuesPaginator->firstItem();
    @endphp
    @if ($numberInList >= 1)
    <ol class="pl-2">
        @foreach($issuesPaginator as $issue)
            <li class="py-3">
                {{ $numberInList++ }}.
                <a class="py-2 hover:text-purple-700" href="{{ route('issuesShow', ['issue' => $issue->id]) }}">
                    {{ $issue->subject }}
                </a>
                <div class="pl-6 text-sm text-gray-600">
                    Создана {{ $issue->created_at->diffForHumans() }}, комментариев - {{ count($issue->comments) }} <span class="font-bold">{{count($issue->comments) > 0 ? '(последний ' . $issue->comments()->latest()->first()->created_at->format('d.m.Y H:i') . ')' : ''}}</span>
                    @if(auth()->user()->isSeniorManager() || auth()->user()->isAdmin())
                        &nbsp;<span class="bg-red-600 text-white rounded">{{ $issue->isAttached() ? '' : '(не прикреплена)'}}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
    @else
        Заявки отсутствуют
    @endif

    {{--  Pagination  --}}
    <div class="max-w-md py-3">
        {{ $issuesPaginator->links() }}
    </div>
@endsection
