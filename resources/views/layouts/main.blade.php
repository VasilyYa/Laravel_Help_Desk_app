<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

    <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
    @yield('custom_js')

    <title>@yield('title')</title>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Lato');

        html {
            font-family: 'Lato', sans-serif;
        }
    </style>
</head>

<body class="bg-green-100 tracking-wider tracking-normal">

<!--Top menu-->
@include('includes.top-menu')

{{--<div class="container w-full lg:w-3/4 flex flex-wrap mx-auto px-2 mt-3">--}}
<div class="{{ (url()->current() === route('adminIndex')) ? 'min-w-1024' : '' }} w-full md:w-4/5 flex flex-wrap mx-auto px-2 mt-3">

    <!--Side menu-->
    @includeIf('includes.side-menu')

    <!--Content-->
    <div class="w-full p-8 mt-0 text-gray-900 leading-normal bg-gray-50 border border-gray-400 border-rounded">
        @yield('content')
    </div>

</div>
</body>
</html>
