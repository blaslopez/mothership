@extends('cockpit::layouts.master')

@section('title', __('cockpit::menu.roles'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">@lang('cockpit::menu.roles')</h4>
        @can('cockpit.roles.create')
            <a href="{{ route('cockpit.roles.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>@lang('cockpit::roles.create')
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>@lang('cockpit::roles.name')</th>
                        <th>@lang('cockpit::roles.description')</th>
                        <th>@lang('cockpit::roles.permissions')</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->description ?? '—' }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $role->permissions->count() }} @lang('cockpit::roles.permissions')
                                </span>
                            </td>
                            <td class="text-end">
                                @can('cockpit.roles.edit')
                                    <a href="{{ route('cockpit.roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                                @can('cockpit.roles.delete')
                                    <form action="{{ route('cockpit.roles.destroy', $role) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ __('cockpit::roles.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">@lang('cockpit::roles.empty')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $roles->links() }}</div>
@endsection
