<?php

namespace Shipu\Aamarpay;

use Illuminate\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class AamarpayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->setupConfig();
    }
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerAamarpay();
    }
    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/aamarpay.php');
        // Check if the application is a Laravel OR Lumen instance to properly merge the configuration file.
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('aamarpay.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('aamarpay');
        }
        $this->mergeConfigFrom($source, 'aamarpay');
    }
    /**
     * Register Talk class.
     */
    protected function registerAamarpay()
    {
        $this->app->bind('aamarpay', function (Container $app) {
            return new Aamarpay($app['config']->get('aamarpay'));
        });
        $this->app->alias('aamarpay', Aamarpay::class);
    }
    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'aamarpay'
        ];
    }
}
