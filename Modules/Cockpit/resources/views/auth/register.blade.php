@extends('cockpit::layouts.auth')

@section('title', __('cockpit::auth.register'))

@section('content')
    <h5 class="mb-4">@lang('cockpit::auth.register')</h5>

    <form action="{{ route('cockpit.register') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="name">@lang('cockpit::auth.name')</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="email">@lang('cockpit::auth.email')</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">@lang('cockpit::auth.password')</label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="password_confirmation">@lang('cockpit::auth.password_confirm')</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">@lang('cockpit::auth.register')</button>
    </form>

    <hr>

    <div class="text-center">
        <a href="{{ route('cockpit.login') }}" class="text-decoration-none">
            @lang('cockpit::auth.have_account')
        </a>
    </div>
@endsection
