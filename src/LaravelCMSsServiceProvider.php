<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager;

use AdUpFastcheckouts\adupiov3modulesmanager\Contracts\RepositoryInterface;
use AdUpFastcheckouts\adupiov3modulesmanager\Exceptions\InvalidActivatorClass;
use AdUpFastcheckouts\adupiov3modulesmanager\Support\Stub;

class LaravelCMSsServiceProvider extends CMSsServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->registerNamespaces();
        $this->registerCMSs();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'cmss');
    }

    /**
     * Setup stub path.
     */
    public function setupStubPath()
    {
        $path = $this->app['config']->get('cmss.stubs.path') ?? __DIR__ . '/Commands/stubs';
        Stub::setBasePath($path);

        $this->app->booted(function ($app) {
            /** @var RepositoryInterface $cmsRepository */
            $cmsRepository = $app[RepositoryInterface::class];
            if ($cmsRepository->config('stubs.enabled') === true) {
                Stub::setBasePath($cmsRepository->config('stubs.path'));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function registerServices()
    {
        $this->app->singleton(Contracts\RepositoryInterface::class, function ($app) {
            $path = $app['config']->get('cmss.paths.cmss');

            return new Laravel\LaravelFileRepository($app, $path);
        });
        $this->app->singleton(Contracts\ActivatorInterface::class, function ($app) {
            $activator = $app['config']->get('cmss.activator');
            $class = $app['config']->get('cmss.activators.' . $activator)['class'];

            if ($class === null) {
                throw InvalidActivatorClass::missingConfig();
            }

            return new $class($app);
        });
        $this->app->alias(Contracts\RepositoryInterface::class, 'cmss');
    }
}
