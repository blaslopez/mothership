<?php

namespace Modules\Cockpit\app\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Cockpit\Models\UserPreference;

class PreferenceManager
{
    // Default preference values
    private array $defaults = [
        'global' => [
            'theme'  => 'light',
            'layout' => 'default',
            'locale' => 'en',
        ],
    ];

    public function get(string $module, string $key, mixed $default = null): mixed
    {
        if (Auth::check()) {
            $pref = UserPreference::where('user_id', Auth::id())
                ->where('module', $module)
                ->where('key', $key)
                ->first();

            return $pref?->value ?? $default ?? $this->defaults[$module][$key] ?? null;
        }

        // Guest: return default (localStorage is handled client-side)
        return $default ?? $this->defaults[$module][$key] ?? null;
    }

    public function set(string $module, string $key, mixed $value): void
    {
        if (! Auth::check()) {
            return; // Guest preferences are managed client-side
        }

        UserPreference::updateOrCreate(
            ['user_id' => Auth::id(), 'module' => $module, 'key' => $key],
            ['value'   => $value]
        );
    }

    public function all(string $module): array
    {
        $defaults = $this->defaults[$module] ?? [];

        if (! Auth::check()) {
            return $defaults;
        }

        $stored = UserPreference::where('user_id', Auth::id())
            ->where('module', $module)
            ->pluck('value', 'key')
            ->toArray();

        return array_merge($defaults, $stored);
    }

    /**
     * Migrate guest preferences from localStorage to DB on login.
     * Called via POST /cockpit/preferences/sync
     */
    public function syncFromClient(array $preferences): void
    {
        if (! Auth::check()) {
            return;
        }

        foreach ($preferences as $module => $items) {
            if (! is_array($items)) {
                continue;
            }
            foreach ($items as $key => $value) {
                $this->set($module, $key, $value);
            }
        }
    }

    public function addDefaults(string $module, array $defaults): void
    {
        $this->defaults[$module] = array_merge(
            $this->defaults[$module] ?? [],
            $defaults
        );
    }
}
