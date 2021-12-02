@extends('layouts.main')


@section('title')
    {{__('User management')}}
@endsection


@section('custom_js')
    <script defer>
        function sendDeleteForm(deletedId) {
            const infoModal = document.getElementById("info-modal");
            const infoModalParagraph = document.getElementById("info-modal-paragraph");
            const infoModalBtn = document.getElementById("info-modal-btn");
            const errorModal = document.getElementById("error-modal");
            const errorModalParagraph = document.getElementById("error-modal-paragraph");
            const errorModalBtn = document.getElementById("error-modal-btn");

            let formData = new FormData();
            formData.append("_token", "{{ csrf_token() }}");
            formData.append("_method", "delete");
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "/admin/delete-user/" + deletedId);
            xhr.send(formData);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 204) {
                        infoModalParagraph.innerText = "{{__('User has been deleted!')}}";
                        //console.log('Пользователь id=' + {{-- $user->id --}} + ' удален');
                        infoModalBtn.addEventListener('click', function () {
                            window.location.href = "{{route('adminIndex')}}";
                        });
                        infoModal.style.display = "block";
                    } else {
                        let errorObj = JSON.parse(xhr.responseText);
                        let errorText = "";
                        for (const [key, value] of Object.entries(errorObj.errors)) {
                            errorText += value + '\n';
                        }
                        errorModalParagraph.innerText = errorText;
                        errorModalBtn.addEventListener('click', function () {
                            errorModal.style.display = "none";
                            this.removeEventListener('click', arguments.callee);
                        });
                        errorModal.style.display = "block";
                    }
                }
            }
        }
        document.onreadystatechange = function () {
            if (document.readyState === "complete") {
                const deleteButtons = document.getElementsByName('deleteBtn');
                for(let i = 0; i < deleteButtons.length; i++) {
                    deleteButtons[i].addEventListener('click', function (e) {
                        sendDeleteForm(this.value); //each button's value is id to delete
                    });
                }
            }
        };
    </script>
@endsection


@section('content')

    {{--  Header  --}}
    <div class="font-sans">
        <h1 class="font-sans break-normal text-gray-900 pt-0 pb-8 text-xl">@yield('title')</h1>
    </div>

    {{--  List all entities  --}}
    @php
        /** @var Illuminate\Pagination\LengthAwarePaginator $usersPaginator */
        $numberInList = $usersPaginator->firstItem();
        $visible = ($usersPaginator[0] === null) ? [] : $usersPaginator[0]->getVisible();
    @endphp
    <div class="container flex justify-center mx-auto">
        <div class="flex flex-col">
            <div class="w-full">
                <div class="border-b border-gray-200 shadow">

                    @if(!empty($visible))
                    <table class="divide-y divide-gray-300 ">
                        <thead class="bg-gray-50">
                        <tr>
                                <th class="pl-4 py-2 text-xs text-gray-500">№</th>
                            @foreach($visible as $attr)
                                <th class="px-3 py-2 text-xs text-gray-500">{{ $attr }}</th>
                            @endforeach
                                <th class="px-3 py-2 text-xs text-gray-500">
                                    {{ __('Edit') }}
                                </th>
                                <th class="px-3 py-2 text-xs text-gray-500">
                                    {{ __('Delete') }}
                                </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-300">
                        @php /** @var App\Models\User $user */ @endphp
                        @foreach($usersPaginator as $user)
                            <tr class="whitespace-nowrap">
                                    <td class="pl-4 py-4 text-sm text-gray-900">{{ $numberInList++ }}</td>
                                @foreach($visible as $attr)
                                    <td class="px-3 py-4 text-sm text-gray-900">{{ $user->$attr }}</td>
                                @endforeach
                                    <td class="px-2 py-4">
                                        <a href="{{ route('adminEditUser', ['user' => $user->id]) }}" class="px-4 py-1 text-sm text-blue-600 bg-blue-200 rounded-full">{{ __('Edit') }}</a>
                                    </td>
                                    <td class="px-2 py-4">
                                        <button class="px-4 py-1 text-sm text-red-400 bg-red-200 rounded-full" name="deleteBtn" value="{{ $user->id }}">{{ __('Delete') }}</button>
                                    </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        Пользователи отсутствуют
                    @endif

                </div>
            </div>

            {{--  Pagination  --}}
            <div class="max-w-md py-3">
                {{ $usersPaginator->links() }}
            </div>

        </div>
    </div>

    @include('includes.modals')

@endsection
