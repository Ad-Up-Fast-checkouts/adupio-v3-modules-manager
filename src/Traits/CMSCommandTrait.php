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
        $module = $this->argument('module') ?: app('modules')->getUsedNow();

        $module = app('modules')->findOrFail($module);

        return $module->getStudlyName();
    }
}
