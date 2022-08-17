<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Traits;

trait CMSCommandTrait
{
    /**
     * Get the module name.
     *
     * @return string
     */
    public function getCMSName()
    {
        $cms = $this->argument('cms') ?: app('cmss')->getUsedNow();

        $cms = app('cmss')->findOrFail($cms);

        return $cms->getStudlyName();
    }
}
