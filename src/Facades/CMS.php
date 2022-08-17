<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Facades;

use Illuminate\Support\Facades\Facade;

class CMS extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cmss';
    }
}
