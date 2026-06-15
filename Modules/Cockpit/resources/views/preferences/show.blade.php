@extends('cockpit::layouts.master')

@section('title', __('cockpit::preferences.title'))

@section('content')
    <h4 class="mb-4">@lang('cockpit::preferences.title')</h4>

    @auth
    <form action="{{ route('cockpit.preferences.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title">@lang('cockpit::preferences.appearance')</h6>

                <div class="mb-3">
                    <label class="form-label">@lang('cockpit::preferences.theme')</label>
                    <div class="d-flex gap-3">
                        @foreach(['light', 'dark'] as $t)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="theme"
                                       id="theme_{{ $t }}" value="{{ $t }}"
                                       {{ ($prefs['theme'] ?? 'light') === $t ? 'checked' : '' }}>
                                <label class="form-check-label" for="theme_{{ $t }}">
                                    @lang('cockpit::preferences.theme_' . $t)
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">@lang('cockpit::preferences.layout')</label>
                    <div class="d-flex gap-3">
                        @foreach(['default', 'compact'] as $l)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="layout"
                                       id="layout_{{ $l }}" value="{{ $l }}"
                                       {{ ($prefs['layout'] ?? 'default') === $l ? 'checked' : '' }}>
                                <label class="form-check-label" for="layout_{{ $l }}">
                                    @lang('cockpit::preferences.layout_' . $l)
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="locale">@lang('cockpit::preferences.locale')</label>
                    <select class="form-select" id="locale" name="locale" style="max-width: 200px;">
                        <option value="en" {{ ($prefs['locale'] ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                        <option value="ca" {{ ($prefs['locale'] ?? 'en') === 'ca' ? 'selected' : '' }}>Català</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">@lang('cockpit::preferences.save')</button>
    </form>
    @else
    {{-- Guest: preferences managed via localStorage --}}
    <div class="card" id="guest-preferences-app">
        <div class="card-body">
            <h6 class="card-title">@lang('cockpit::preferences.appearance')</h6>
            <p class="text-muted small">@lang('cockpit::preferences.guest_notice')</p>

            <div class="mb-3">
                <label class="form-label">@lang('cockpit::preferences.theme')</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="theme" value="light"
                               :checked="prefs.theme === 'light'" @change="set('theme', 'light')">
                        <label class="form-check-label">@lang('cockpit::preferences.theme_light')</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="theme" value="dark"
                               :checked="prefs.theme === 'dark'" @change="set('theme', 'dark')">
                        <label class="form-check-label">@lang('cockpit::preferences.theme_dark')</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">@lang('cockpit::preferences.layout')</label>
                <select class="form-select" style="max-width: 200px;" @change="set('layout', $event.target.value)">
                    <option value="default"  :selected="prefs.layout === 'default'">@lang('cockpit::preferences.layout_default')</option>
                    <option value="compact"  :selected="prefs.layout === 'compact'">@lang('cockpit::preferences.layout_compact')</option>
                </select>
            </div>

            <p class="text-success small mt-3" v-if="saved">@lang('cockpit::preferences.saved_locally')</p>
        </div>
    </div>

    @push('scripts')
    <script>
    const { createApp, ref, reactive } = Vue;
    createApp({
        setup() {
            const saved = ref(false);
            const prefs = reactive({
                theme:  Preferences.get('global', 'theme')  || 'light',
                layout: Preferences.get('global', 'layout') || 'default',
            });

            function set(key, value) {
                prefs[key] = value;
                Preferences.set('global', key, value);
                saved.value = true;
                setTimeout(() => saved.value = false, 2000);
            }

            return { prefs, saved, set };
        }
    }).mount('#guest-preferences-app');
    </script>
    @endpush
    @endauth
@endsection
