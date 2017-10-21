<?php

namespace Nzesalem\Lastus;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias(Lastus::class, 'lastus');

        $this->app->singleton(Lastus::class, function () {
            return new Lastus();
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }
}
