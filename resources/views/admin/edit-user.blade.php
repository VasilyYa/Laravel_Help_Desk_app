@extends('layouts.main')


@section('title')
    {{ __('Edit the user') }}
@endsection


@section('custom_js')
    <script defer>
        function sendEditForm() {
            const infoModal = document.getElementById("info-modal");
            const infoModalParagraph = document.getElementById("info-modal-paragraph");
            const infoModalBtn = document.getElementById("info-modal-btn");
            const errorModal = document.getElementById("error-modal");
            const errorModalParagraph = document.getElementById("error-modal-paragraph");
            const errorModalBtn = document.getElementById("error-modal-btn");

            let formData = new FormData(document.forms.editForm);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('adminUpdateUser', ['user' => $user->id]) }}");
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.send(formData);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 204) {
                        console.log('Пользователь id=' + {{ $user->id }} + ' отредактирован');
                        infoModalParagraph.innerText = "{{__('User has been edited!')}}";
                        infoModalBtn.addEventListener('click', function () {
                            window.location.reload();
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
                        });
                        errorModal.style.display = "block";
                    }
                }
            }
        }
    </script>
@endsection


@section('content')

    {{--  Header  --}}
    <div class="font-sans">
        <h1 class="font-sans break-normal text-gray-900 pt-0 pb-2 text-xl">@yield('title')</h1>
        <hr class="border-b border-gray-400">
    </div>

    {{-- Edit form --}}
    <div class="p-2">
        <form name="editForm" onsubmit="sendEditForm(); return false">
            @method('PUT')
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <label>
                {{ __('First Name') }}:
                <input type="text" class="block border border-solid border-black rounded p-1 mb-2" name="name" value="{{ $user->name }}">
            </label>
            <label>
                {{ __('Last Name') }}:
                <input type="text" class="block border border-solid border-black rounded p-1 mb-2" name="last_name" value="{{ $user->last_name }}">
            </label>
            <label>
                {{ __('Email') }}:
                <input type="email" class="block border border-solid border-black rounded p-1 mb-2" name="email" value="{{ $user->email }}">
            </label>
            <label>
                {{ __('Phone') }}:
                <input type="text" class="block border border-solid border-black rounded p-1 mb-2" name="phone" value="{{ $user->phone }}">
            </label>
            <label>
                {{ __('Role') }}:
                <select class="block" name="role_id">
                    <option disabled {{ ($user->role()->first() === null) ? 'selected' : '' }} value="">выберите</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{($user->role()->first() !== null && $role->id == $user->role()->first()->id) ? 'selected' : '' }}>{{ $role->description }}</option>
                    @endforeach
                </select>
            </label>
            <div class="pt-6 pb-2">
                <span class="font-bold">Изменить пароль пользователя</span><br> (при необходимости):
            </div>
            <label>
                {{ __('Password') }}:
                <input type="password" class="block border border-solid border-black rounded p-1 mb-2" name="password" value="default" readonly onfocus="this.removeAttribute('readonly');">
            </label>
            <label>
                {{ __('Confirm Password') }}:
                <input type="password" class="block border border-solid border-black rounded p-1 mb-2" name="password_confirmation" value="default" readonly onfocus="this.removeAttribute('readonly');">
            </label>

            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded" type="submit">Применить изменения</button>
        </form>
    </div>

    @include('includes.modals')

@endsection
