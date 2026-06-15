@extends('cockpit::layouts.auth')

@section('title', __('cockpit::auth.login'))

@section('content')
    <h5 class="mb-4">@lang('cockpit::auth.login')</h5>

    <form action="{{ route('cockpit.login') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="email">@lang('cockpit::auth.email')</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autofocus>
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

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">@lang('cockpit::auth.remember')</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">@lang('cockpit::auth.login')</button>
    </form>

    <hr>

    <div class="text-center">
        <a href="{{ route('cockpit.register') }}" class="text-decoration-none">
            @lang('cockpit::auth.no_account')
        </a>
    </div>
@endsection

@push('scripts')
<script>
// After login, sync localStorage preferences to server
document.querySelector('form').addEventListener('submit', function() {
    const prefs = Preferences.load();
    if (Object.keys(prefs).length === 0) return;

    const input = document.createElement('input');
    input.type  = 'hidden';
    input.name  = '_preferences';
    input.value = JSON.stringify(prefs);
    this.appendChild(input);
});
</script>
@endpush
