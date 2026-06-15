@extends('cockpit::layouts.master')

@section('title', __('cockpit::menu.users'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">@lang('cockpit::menu.users')</h4>
        @can('cockpit.users.create')
            <a href="{{ route('cockpit.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>@lang('cockpit::users.create')
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>@lang('cockpit::users.name')</th>
                        <th>@lang('cockpit::users.email')</th>
                        <th>@lang('cockpit::users.roles')</th>
                        <th>@lang('cockpit::users.status')</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-secondary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->is_active ? __('cockpit::users.active') : __('cockpit::users.inactive') }}
                                </span>
                            </td>
                            <td class="text-end">
                                @can('cockpit.users.edit')
                                    <a href="{{ route('cockpit.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                                @can('cockpit.users.delete')
                                    <form action="{{ route('cockpit.users.destroy', $user) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ __('cockpit::users.confirm_delete') }}')">
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
                            <td colspan="5" class="text-center text-muted py-4">@lang('cockpit::users.empty')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $users->links() }}</div>
@endsection
