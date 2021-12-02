@extends('layouts.main')


@section('title')
    {{ __('Register a new user') }}
@endsection


@section('custom_js')
    <script defer>
        function sendCreateForm() {
            const infoModal = document.getElementById("info-modal");
            const infoModalParagraph = document.getElementById("info-modal-paragraph");
            const infoModalBtn = document.getElementById("info-modal-btn");
            const errorModal = document.getElementById("error-modal");
            const errorModalParagraph = document.getElementById("error-modal-paragraph");
            const errorModalBtn = document.getElementById("error-modal-btn");

            let formData = new FormData(document.forms.createForm);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('adminStoreUser') }}");
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.send(formData);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 201) {
                        infoModalParagraph.innerText = "{{__('User has been added!')}}";
                        infoModalBtn.addEventListener('click', function () {
                            window.location.href = "{{ route('adminIndex') }}";
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
        <form name="createForm" onsubmit="sendCreateForm(); return false" method="post">
            @csrf
            <label>
                {{ __('First Name') }}:
                <input type="text" class="block border border-solid border-black rounded p-1 mb-2" name="name">
            </label>
            <label>
                {{ __('Last Name') }}:
                <input type="text" class="block border border-solid border-black rounded p-1 mb-2" name="last_name">
            </label>
            <label>
                {{ __('Email') }}:
                <input type="email" class="block border border-solid border-black rounded p-1 mb-2" name="email">
            </label>
            <label>
                {{ __('Phone') }}:
                <input type="text" class="block border border-solid border-black rounded p-1 mb-2" name="phone">
            </label>
            <label>
                {{ __('Role') }}:
                <select class="block" name="role_id">
                    <option disabled selected value="">выберите</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->description }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                {{ __('Password') }}:
                <input type="password" class="block border border-solid border-black rounded p-1 mb-2" name="password" value="" readonly onfocus="this.removeAttribute('readonly');">
            </label>
            <label>
                {{ __('Confirm Password') }}:
                <input type="password" class="block border border-solid border-black rounded p-1 mb-2" name="password_confirmation" value="" readonly onfocus="this.removeAttribute('readonly');">
            </label>

            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-2 rounded" type="submit">{{ __('Register') }}</button>
        </form>
    </div>

    @include('includes.modals')

@endsection
