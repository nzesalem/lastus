<?php

namespace Nzesalem\Lastus;

use Illuminate\Support\Facades\Blade;
use Nzesalem\Lastus\Middleware\LastusUserStatus;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Lastus::class, function ($app) {
            return new Lastus($app);
        });

        $this->app->alias(Lastus::class, 'lastus');

    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $this->app['router']->aliasMiddleware('status', LastusUserStatus::class);

        // Register blade directives
        $this->bladeDirectives();
    }

    /**
     * Registers the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
        if (! class_exists(Blade::class)) {
            return;
        }
        Blade::directive('status', function($expression) {
            return "<?php if (\\Lastus::userIsCurrently({$expression})) : ?>";
        });
        Blade::directive('endstatus', function($expression) {
            return "<?php endif; ?>";
        });
    }
}
