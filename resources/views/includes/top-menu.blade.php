<div class="container w-full lg:w-3/4 flex mx-auto px-2 mt-0 justify-between">

    {{--  actions menu  --}}
    <div class="flex flex-nowrap justify-start">
        {{--  link to list all issues  --}}
        <div class="mt-5 text-sm font-bold text-green-800 whitespace-nowrap">
            <div class="p-4">
                <a class="py-6 hover:text-purple-700" href="{{route('issuesListForUser')}}">
                    {{ (auth()->user()->isSeniorManager() || auth()->user()->isAdmin()) ? __('All issues') : __('Current issues') }}
                </a>
            </div>
        </div>
        {{--  link to list all free issues  --}}
        @if(!auth()->user()->isClient())
        <div class="mt-5 text-sm font-bold text-green-800 whitespace-nowrap">
            <div class="p-4">
                <a class="py-6 hover:text-purple-700" href="{{route('issuesListNotAttached')}}">
                    {{ __('Free issues') }}
                </a>
            </div>
        </div>
        @endif
        {{--  link to create new issue  --}}
        @if(auth()->user()->isClient())
        <div class="mt-5 text-sm font-bold text-green-800 whitespace-nowrap">
            <div class="p-4">
                <a class="py-6 hover:text-purple-700" href="{{route('issuesCreate')}}">
                    {{ __('New issue') }}
                </a>
            </div>
        </div>
        @endif
        {{--  link to user management panel  --}}
        @if(auth()->user()->isAdmin())
        <div class="mt-5 text-sm font-bold text-green-700 dark:text-gray-500 whitespace-nowrap">
            <div class="p-4">
                <a class="py-6 hover:text-purple-700" href="{{route('adminIndex')}}">
                    {{ __('Management') }}
                </a>
            </div>
        </div>
        @endif
    </div>


    {{--  auth menu  --}}
    <div class="flex flex-nowrap justify-end">

        <!-- welcome user -->
        <div class="mt-5 text-sm text-gray-700 dark:text-gray-500 whitespace-nowrap">
            <div class="p-4">
                {{ __('User') }}: <span class="font-bold"> {{ auth()->user()->name }} </span>
            </div>
        </div>

        <!-- link to Dashboard -->
        <div class="mt-5 text-sm text-gray-700 dark:text-gray-500 underline whitespace-nowrap">
            <div class="p-4">
                <a class="py-6 hover:text-purple-700" href="{{ route('dashboard') }}">
                    {{ __('Dashboard') }}
                </a>
            </div>
        </div>

        <!-- link to logout -->
        <div class="mt-5 mr-5 text-sm text-gray-700 dark:text-gray-500 underline whitespace-nowrap">
            <div class="p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="hover:text-purple-700" href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>

    </div>


</div>
