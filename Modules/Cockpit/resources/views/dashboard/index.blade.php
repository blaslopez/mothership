@extends('cockpit::layouts.master')

@section('title', __('cockpit::dashboard.title'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">@lang('cockpit::dashboard.title')</h4>
        @guest
            <span class="badge bg-secondary">@lang('cockpit::dashboard.guest_mode')</span>
        @else
            <span class="badge bg-success">{{ Auth::user()->name }}</span>
        @endguest
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted">@lang('cockpit::dashboard.theme')</h6>
                    <p class="card-text fw-semibold">{{ $prefs['theme'] ?? 'light' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted">@lang('cockpit::dashboard.layout')</h6>
                    <p class="card-text fw-semibold">{{ $prefs['layout'] ?? 'default' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted">@lang('cockpit::dashboard.locale')</h6>
                    <p class="card-text fw-semibold">{{ $prefs['locale'] ?? 'en' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
