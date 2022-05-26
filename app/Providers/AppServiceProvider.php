<?php

namespace App\Providers;

use App\Layers\Persistence\Database;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Log::alert("register called");
        $this->app->singleton(Database::class, function ($app) {
            return new Database();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Log::alert("boot called");
    }
}
