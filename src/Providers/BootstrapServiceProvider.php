<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Providers;

use Illuminate\Support\ServiceProvider;
use AdUpFastcheckouts\adupiov3modulesmanager\Contracts\RepositoryInterface;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot(): void
    {
        $this->app[RepositoryInterface::class]->boot();
    }

    /**
     * Register the provider.
     */
    public function register(): void
    {
        $this->app[RepositoryInterface::class]->register();
    }
}
