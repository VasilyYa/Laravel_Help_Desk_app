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
            xhr.open("POST", "{{ route('commentsStore') }}");
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.send(formData);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 201) {
                        infoModalParagraph.innerText = "{{__('Comment has been successfully added!')}}";
                        infoModal.style.display = "block";
                        let headerWithId = xhr.getResponseHeader('Location'); // entities/id
                        console.log('Комментарий добавлен.\n Id: ' + headerWithId.split('/').reverse()[0]);

                        infoModalBtn.addEventListener('click', function () {
                            window.location.reload();
                        });
                    } else {
                        let errorObj = JSON.parse(xhr.responseText);
                        let errorText = "";
                        for (const [key, value] of Object.entries(errorObj.errors)) {
                            errorText += value + '\n';
                        }
                        errorModalParagraph.innerText = errorText;
                        errorModal.style.display = "block";

                        errorModalBtn.addEventListener('click', function () {
                            errorModal.style.display = "none";
                            this.removeEventListener('click', arguments.callee);
                        });
                    }
                }
            }
        }
        function sendDeleteForm() {
            const infoModal = document.getElementById("info-modal");
            const infoModalParagraph = document.getElementById("info-modal-paragraph");
            const infoModalBtn = document.getElementById("info-modal-btn");
            const errorModal = document.getElementById("error-modal");
            const errorModalParagraph = document.getElementById("error-modal-paragraph");
            const errorModalBtn = document.getElementById("error-modal-btn");

            let formData = new FormData(document.forms.deleteForm);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('issuesDestroy', ['issue' => $issue]) }}");
            xhr.send(formData);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 204) {
                        infoModalParagraph.innerText = "{{__('Issue has been successfully closed!')}}";
                        console.log('Заявка id=' + {{ $issue->id }} + ' удалена');
                        infoModalBtn.addEventListener('click', function () {
                            window.location.href = "{{route('issuesListForUser')}}";
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
        function sendAttachForm() {
            const infoModal = document.getElementById("info-modal");
            const infoModalParagraph = document.getElementById("info-modal-paragraph");
            const infoModalBtn = document.getElementById("info-modal-btn");
            const errorModal = document.getElementById("error-modal");
            const errorModalParagraph = document.getElementById("error-modal-paragraph");
            const errorModalBtn = document.getElementById("error-modal-btn");

            let formData = new FormData(document.forms.attachForm);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('issuesAttach', ['issue' => $issue->id]) }}");
            xhr.send(formData);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        infoModalParagraph.innerText = "{{__('Issue has been successfully attached!')}}";
                        console.log('Заявка id=' + {{ $issue->id }} + ' взята в работу');
                        infoModalBtn.addEventListener('click', function () {
                            window.location.href = "{{route('issuesShow', ['issue' => $issue->id])}}";
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
    </script>
@endsection


@section('content')

    {{--  Header  --}}
    <div class="font-sans">
        <h1 class="font-sans break-normal text-gray-900 pt-0 pb-2 text-xl">{{__('Issue')}} № {{$issue->id}}</h1>
        <hr class="border-b border-gray-400">
    </div>


    <p class="p-3">
        <span class="underline">Тема:</span>
        {{ $issue->subject }}
    </p>

    <p class="p-3">
        <span class="underline">Описание:</span>
        {{ $issue->detail }}
    </p>

    <p class="p-3">
        <span class="underline">Дата создания:</span>
        {{ $issue->created_at->format('d.m.Y h:m') }} ({{$issue->created_at->diffForHumans()}})
    </p>

    <p class="p-3">
        <span class="underline">Комментарии:</span>
    </p>
    <ul class="pl-10 list-disc">
        @if ($issue->comments()->first() !== null)
            @foreach($issue->comments as $comment)
                <li>
                    <span class="underline">
                        @if($comment->author_id == auth()->user()->id)
                            От Вас
                        @else
                            От {{ $comment->author_full_name }}
                        @endif
                    </span>
                    &nbsp;
                    ({{ $comment->created_at->format('d.m.y h:m') }}):
                    <br>

                    <div class="ml-6 italic text-sm">
                        {{ $comment->text }}
                    </div>
                </li>
            @endforeach
        @else
            <span class="text-sm">Комментарии отсутствуют</span>
        @endif
    </ul>

    {{--  Форма комментариев  --}}
    @if(auth()->user()->isClient() || auth()->user()->isManagerOfIssue($issue))
    <div class="pt-4">
        <form name="createForm" onsubmit="sendCreateForm(); return false" method="POST">
            @csrf
            <input type="hidden" name="author_id" value="{{auth()->user()->id}}">
            <input type="hidden" name="issue_id" value="{{$issue->id}}">
            <label>
                Оставить комментарий к заявке:
                <div>
                    <textarea class="w-4/5 md:w-1/2 lg:w-1/3 h-36" name="text"></textarea>
                </div>
            </label>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">OK</button>
        </form>
    </div>
    @endif

    {{--  Форма закрытия заявки  --}}
    @if (auth()->user()->isClient())
    <hr class="border-gray-400 mt-8">
    <div class="pt-4">
        <form name="deleteForm" onsubmit="sendDeleteForm(); return false" method="POST">
            @csrf
            @method('delete')
            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" type="submit">Закрыть заявку</button>
        </form>
    </div>
    @endif

    {{--  Форма прикепления свободной заявки менеджером на себя  --}}
    @if (!$issue->isAttached() && auth()->user()->isManager())
        <hr class="border-gray-400 mt-8">
        <div class="pt-4">
            <form name="attachForm" onsubmit="sendAttachForm(); return false" method="POST">
                @csrf
                <input type="hidden" name="issue_id" value="{{ $issue->id }}">
                <input type="hidden" name="manager_id" value="{{ auth()->user()->id }}">
                <button class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded" type="submit">Взять заявку в работу</button>
            </form>
        </div>
    {{--  либо прикепления заявки старшим менеджером на выбранного менеджера  --}}
    @elseif(auth()->user()->isSeniorManager() || auth()->user()->isAdmin())
        <hr class="border-gray-400 mt-8">
        <div class="pt-4">
            <form name="attachForm" onsubmit="sendAttachForm(); return false" method="POST">
                @csrf
                <input type="hidden" name="issue_id" value="{{ $issue->id }}">
                <label for="manager">Прикрепить на менеджера:</label>
                <select name="manager_id" id="manager">
                    <option disabled {{ (!isset($attachedManager)) ? 'selected' : '' }} value="">выберите</option>
                    @foreach($managers as $manager)
                            <option value="{{ $manager->id}}" {{(isset($attachedManager) && $manager->id == $attachedManager->id) ? 'selected' : '' }}>{{$manager->full_name}} (заявок - {{count($manager->issues()->get())}})</option>
                    @endforeach
                </select>
                <button class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded" type="submit">Сохранить изменения</button>
            </form>
        </div>
    @endif

    @include('includes.modals')

@endsection

