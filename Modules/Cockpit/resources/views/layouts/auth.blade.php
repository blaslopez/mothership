<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="{{ $theme ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    @library('bootstrap')
    @library('bootstrap-icons')

    <link rel="stylesheet" href="{{ asset('vendor/themes/' . ($theme ?? 'light') . '/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body class="auth-layout bg-body-secondary">

    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        <div class="auth-card card shadow-sm" style="width: 100%; max-width: 420px;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">{{ config('app.name') }}</h4>
                </div>

                @include('cockpit::layouts.partials.alerts')

                @yield('content')
            </div>

            <div class="card-footer text-center py-3">
                <a href="{{ route('cockpit.dashboard') }}" class="text-muted small">
                    @lang('cockpit::auth.continue_as_guest')
                </a>
            </div>
        </div>
    </div>

    @library('vue')
    @stack('scripts')

</body>
</html>
