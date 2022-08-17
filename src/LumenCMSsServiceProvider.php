<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager;

use AdUpFastcheckouts\adupiov3modulesmanager\Support\Stub;

class LumenCMSsServiceProvider extends CMSsServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->setupStubPath();
    }

    /**
     * Register all modules.
     */
    public function register()
    {
        $this->registerNamespaces();
        $this->registerServices();
        $this->registerCMSs();
        $this->registerProviders();
    }

    /**
     * Setup stub path.
     */
    public function setupStubPath()
    {
        Stub::setBasePath(__DIR__ . '/Commands/stubs');

        if (app('cmss')->config('stubs.enabled') === true) {
            Stub::setBasePath(app('cmss')->config('stubs.path'));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function registerServices()
    {
        $this->app->singleton(Contracts\RepositoryInterface::class, function ($app) {
            $path = $app['config']->get('cmss.paths.cmss');

            return new Lumen\LumenFileRepository($app, $path);
        });
        $this->app->singleton(Contracts\ActivatorInterface::class, function ($app) {
            $activator = $app['config']->get('cmss.activator');
            $class = $app['config']->get('cmss.activators.' . $activator)['class'];

            return new $class($app);
        });
        $this->app->alias(Contracts\RepositoryInterface::class, 'cmss');
    }
}
