<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Lumen;

use Illuminate\Support\Str;
use AdUpFastcheckouts\adupiov3modulesmanager\CMS as BaseCMS;

class CMS extends BaseCMS
{
    /**
     * {@inheritdoc}
     */
    public function getCachedServicesPath(): string
    {
        return Str::replaceLast('services.php', $this->getSnakeName() . '_module.php', $this->app->basePath('storage/app/') . 'services.php');
    }

    /**
     * {@inheritdoc}
     */
    public function registerProviders(): void
    {
        foreach ($this->get('providers', []) as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerAliases(): void
    {
    }
}
