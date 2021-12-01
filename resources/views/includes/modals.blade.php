{{-- information modal window --}}
<div class="fixed hidden inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="info-modal">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{__('Successful')}}!</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="info-modal-paragraph">
                    {{-- текст сообщения --}}
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="info-modal-btn" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

{{-- error modal window --}}
<div class="fixed hidden inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="error-modal">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5 L 20 20 M20 5 L 5 20"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{__('Error')}}!</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="error-modal-paragraph">
                    {{-- текст сообщения --}}
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="error-modal-btn" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>
