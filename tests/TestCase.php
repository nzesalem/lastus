<?php

namespace Nzesalem\Lastus\Tests;

use Nzesalem\Lastus\Facade;
use Nzesalem\Lastus\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Class TestCase
 *
 * @package Lastus
 */
abstract class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadLaravelMigrations(['--database' => 'lastustest']);
        $this->artisan('migrate', ['--database' => 'lastustest']);
        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });
    }
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // set up database configuration
        $app['config']->set('database.default', 'lastustest');
        $app['config']->set('database.connections.lastustest', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
    /**
     * Get Sluggable package providers.
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }
    /**
     * Load package alias
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Lastus' => Facade::class,
        ];
    }
}
