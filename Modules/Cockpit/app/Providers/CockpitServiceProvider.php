<?php

namespace Modules\Cockpit\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Cockpit\Http\Middleware\LoadUserPreferences;

class CockpitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Cockpit', 'Database/migrations'));
        $this->loadRoutesFrom(module_path('Cockpit', 'Routes/web.php'));
        $this->loadViewsFrom(module_path('Cockpit', 'Resources/views'), 'cockpit');
        $this->loadTranslationsFrom(module_path('Cockpit', 'Resources/lang'), 'cockpit');

        // Register module permissions on boot
        $this->registerPermissions();

        // Register menu items
        $this->registerMenuItems();
    }

    public function register(): void
    {
        // Point Laravel auth to the Cockpit User model
        Config::set('auth.providers.users.model', \Modules\Cockpit\Models\User::class);
    }

    private function registerPermissions(): void
    {
        // Permissions are registered via seeder on first run.
        // Modules define their permissions as an array for reference.
        // See Database/seeders/PermissionSeeder.php
    }

    private function registerMenuItems(): void
    {
        if (! $this->app->bound('menu')) {
            return;
        }

        $this->app->make('menu')->register('cockpit', [
            [
                'label'      => 'cockpit::menu.users',
                'route'      => 'cockpit.users.index',
                'icon'       => 'bi bi-people',
                'permission' => 'cockpit.users.view',
                'order'      => 100,
            ],
            [
                'label'      => 'cockpit::menu.roles',
                'route'      => 'cockpit.roles.index',
                'icon'       => 'bi bi-shield',
                'permission' => 'cockpit.roles.view',
                'order'      => 110,
            ],
        ]);
    }
}
