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
        $cms = $this->argument('cms') ?: app('modules')->getUsedNow();

        $cms = app('modules')->findOrFail($cms);

        return $cms->getStudlyName();
    }
}
