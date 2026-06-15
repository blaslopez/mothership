@extends('cockpit::layouts.master')

@section('title', __('cockpit::roles.create'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">@lang('cockpit::roles.create')</h4>
        <a href="{{ route('cockpit.roles.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>@lang('cockpit::roles.back')
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('cockpit.roles.store') }}" method="POST">
                @csrf
                @include('cockpit::roles._form')
                <button type="submit" class="btn btn-primary">@lang('cockpit::roles.save')</button>
            </form>
        </div>
    </div>
@endsection
