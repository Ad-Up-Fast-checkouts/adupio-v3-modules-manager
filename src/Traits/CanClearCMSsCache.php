<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Traits;

trait CanClearCMSsCache
{
    /**
     * Clear the modules cache if it is enabled
     */
    public function clearCache()
    {
        if (config('modules.cache.enabled') === true) {
            app('cache')->forget(config('modules.cache.key'));
        }
    }
}
