<?php

namespace App\Providers;

use App\Services\MenuManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('menu', fn() => new MenuManager());
    }

    public function boot(): void
    {
        //
    }
}
