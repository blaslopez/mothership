@extends('cockpit::layouts.master')

@section('title', __('cockpit::users.edit'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">@lang('cockpit::users.edit')</h4>
        <a href="{{ route('cockpit.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>@lang('cockpit::users.back')
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('cockpit.users.update', $user) }}" method="POST">
                @csrf @method('PUT')
                @include('cockpit::users._form')
                <button type="submit" class="btn btn-primary">@lang('cockpit::users.save')</button>
            </form>
        </div>
    </div>
@endsection
