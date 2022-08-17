<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager;

use Illuminate\Support\ServiceProvider;
use AdUpFastcheckouts\adupiov3modulesmanager\Providers\BootstrapServiceProvider;
use AdUpFastcheckouts\adupiov3modulesmanager\Providers\ConsoleServiceProvider;
use AdUpFastcheckouts\adupiov3modulesmanager\Providers\ContractsServiceProvider;

abstract class CMSsServiceProvider extends ServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot()
    {
    }

    /**
     * Register all modules.
     */
    public function register()
    {
    }

    /**
     * Register all modules.
     */
    protected function registerCMSs()
    {
        $this->app->register(BootstrapServiceProvider::class);
    }

    /**
     * Register package's namespaces.
     */
    protected function registerNamespaces()
    {
        $configPath = __DIR__ . '/../config/config.php';
        $stubsPath = dirname(__DIR__) . '/src/Commands/stubs';

        $this->publishes([
            $configPath => config_path('cmss.php'),
        ], 'config');

        $this->publishes([
            $stubsPath => base_path('stubs/sanjayaharshana-stubs'),
        ], 'stubs');
    }

    /**
     * Register the service provider.
     */
    abstract protected function registerServices();

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Contracts\RepositoryInterface::class, 'cmss'];
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(ContractsServiceProvider::class);
    }
}
