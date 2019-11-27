<?php

namespace jjsquady\MikrotikApi;

use Illuminate\Support\ServiceProvider;

class MikrotikServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mikontrollib', function($app) {
            return new Mikrotik();
        });
    }

    public function provides()
    {
        return [
            'mikontrollib'
        ];
    }
}
