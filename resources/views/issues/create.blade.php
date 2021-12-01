@extends('layouts.main')


@section('title')
    {{ __('Current issues') }}
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
            xhr.open("POST", "{{ route('issuesStore') }}");
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.send(formData);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 201) {
                        let location = xhr.getResponseHeader('Location'); // .../entities/id
                        let id = location.split('/').reverse()[0];
                        console.log('Заявка добавлена.\n Id: ' + id);
                        infoModalParagraph.innerText = "{{__('Issue has been successfully added!')}}";
                        infoModalBtn.addEventListener('click', function () {
                            window.location.href = location;
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
        <h1 class="font-sans break-normal text-gray-900 pt-0 pb-2 text-xl">{{__('New issue')}}</h1>
        <hr class="border-b border-gray-400">
    </div>

    <!--Create form-->
    <div class="p-2">
        <form name="createForm" onsubmit="sendCreateForm(); return false" method="POST">
            @csrf
            <input type="hidden" name="client_id" value="{{auth()->user()->id}}">
            <label>
                Тема обращения:
                <input type="text" class="block border border-solid border-black rounded p-1 mb-2 w-4/5 md:w-2/3 lg:w-1/2" name="subject">
            </label>
            <label>
                Описание проблемы:
                <div>
                    <textarea class="w-4/5 md:w-2/3 lg:w-1/2 h-36" name="detail"></textarea>
                </div>
            </label>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Отправить заявку</button>
        </form>
    </div>

    @include('includes.modals')

@endsection
