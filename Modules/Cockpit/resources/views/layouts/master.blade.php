<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="{{ $theme ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    @library('bootstrap')
    @library('bootstrap-icons')

    {{-- Theme stylesheet --}}
    <link rel="stylesheet" href="{{ asset('vendor/themes/' . ($theme ?? 'light') . '/theme.css') }}">

    {{-- App base styles --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body class="layout-{{ $layout ?? 'default' }}">

    {{-- Navbar --}}
    @include('cockpit::layouts.partials.navbar')

    {{-- Sidebar + content --}}
    <div class="d-flex">
        @include('cockpit::layouts.partials.sidebar')

        <main class="flex-grow-1 p-4">
            {{-- Flash messages --}}
            @include('cockpit::layouts.partials.alerts')

            @yield('content')
        </main>
    </div>

    @library('vue')

    {{-- Guest preference loader: reads localStorage and syncs with server --}}
    @include('cockpit::layouts.partials.preference-loader')

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')

</body>
</html>
