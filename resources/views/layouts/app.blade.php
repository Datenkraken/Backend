<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" nonce="{{ csp_nonce() }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script nonce="{{ csp_nonce() }}">
        window.default_locale = "{{ config('app.locale') }}";
        window.fallback_locale = "{{ config('app.fallback_locale') }}";
        window.messages = @json($messages);
		window.csrf = "{{ csrf_token() }}";
    </script>
</head>
<body class="bg-gray-100">
    <div id="app" class="overflow-x-hidden sm:overflow-hidden min-h-screen sm:h-screen flex flex-col">
        @unless (isset($origin) && $origin === 'app')
            @include('layouts.topbar')
        @endunless
        <div class="flex-grow flex flex-col sm:flex-row sm:flex-no-wrap min-h-0 h-full">
            @yield('maincontent')
        </div>
    </div>
</body>
</html>
