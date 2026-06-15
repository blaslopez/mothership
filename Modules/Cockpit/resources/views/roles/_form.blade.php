<div class="mb-3">
    <label class="form-label" for="name">@lang('cockpit::roles.name')</label>
    <input type="text" id="name" name="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $role->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="description">@lang('cockpit::roles.description')</label>
    <input type="text" id="description" name="description"
           class="form-control @error('description') is-invalid @enderror"
           value="{{ old('description', $role->description ?? '') }}">
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label class="form-label">@lang('cockpit::roles.permissions')</label>
    @foreach($permissions as $module => $modulePermissions)
        <div class="mb-3">
            <p class="fw-semibold text-muted small text-uppercase mb-2">{{ $module }}</p>
            <div class="d-flex flex-wrap gap-3">
                @foreach($modulePermissions as $permission)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]"
                               id="perm_{{ $permission->id }}" value="{{ $permission->id }}"
                               {{ in_array($permission->id, old('permissions', $rolePermissions ?? [])) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="perm_{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
