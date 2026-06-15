<div class="mb-3">
    <label class="form-label" for="name">@lang('cockpit::users.name')</label>
    <input type="text" id="name" name="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $user->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="email">@lang('cockpit::users.email')</label>
    <input type="email" id="email" name="email"
           class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $user->email ?? '') }}" required>
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="password">@lang('cockpit::auth.password')
        @isset($user) <small class="text-muted">(@lang('cockpit::users.password_optional'))</small> @endisset
    </label>
    <input type="password" id="password" name="password"
           class="form-control @error('password') is-invalid @enderror"
           {{ !isset($user) ? 'required' : '' }}>
    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="password_confirmation">@lang('cockpit::auth.password_confirm')</label>
    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
</div>

<div class="mb-4">
    <label class="form-label">@lang('cockpit::users.roles')</label>
    <div class="d-flex flex-wrap gap-3">
        @foreach($roles as $role)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="roles[]"
                       id="role_{{ $role->id }}" value="{{ $role->id }}"
                       {{ in_array($role->id, old('roles', $userRoles ?? [])) ? 'checked' : '' }}>
                <label class="form-check-label" for="role_{{ $role->id }}">{{ $role->name }}</label>
            </div>
        @endforeach
    </div>
</div>
