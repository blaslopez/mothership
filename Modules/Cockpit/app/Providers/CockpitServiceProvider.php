<?php

namespace Modules\Cockpit\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Cockpit\app\Services\PreferenceManager;

class CockpitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Cockpit', 'database/migrations'));
        $this->loadRoutesFrom(module_path('Cockpit', 'routes/web.php'));
        $this->loadViewsFrom(module_path('Cockpit', 'resources/views'), 'cockpit');
        $this->loadTranslationsFrom(module_path('Cockpit', 'resources/lang'), 'cockpit');

        $this->registerMenuItems();

        // Share current preferences with all Cockpit views
        View::composer('cockpit::layouts.master', function ($view) {
            $prefs = app(PreferenceManager::class)->all('global');
            $view->with([
                'theme'  => $prefs['theme']  ?? 'light',
                'layout' => $prefs['layout'] ?? 'default',
            ]);
        });
    }

    public function register(): void
    {
        Config::set('auth.providers.users.model', \Modules\Cockpit\Models\User::class);

        $this->app->singleton(PreferenceManager::class);
    }

    private function registerMenuItems(): void
    {
        if (! $this->app->bound('menu')) {
            return;
        }

        $this->app->make('menu')->register('cockpit', [
            [
                'label'      => 'cockpit::cockpit.menu.users',
                'route'      => 'cockpit.users.index',
                'icon'       => 'bi bi-people',
                'permission' => 'cockpit.users.view',
                'order'      => 100,
            ],
            [
                'label'      => 'cockpit::cockpit.menu.roles',
                'route'      => 'cockpit.roles.index',
                'icon'       => 'bi bi-shield',
                'permission' => 'cockpit.roles.view',
                'order'      => 110,
            ],
        ]);
    }
}
