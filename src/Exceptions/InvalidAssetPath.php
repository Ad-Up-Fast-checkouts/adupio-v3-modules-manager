<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Exceptions;

class InvalidAssetPath extends \Exception
{
    public static function missingCMSName($asset)
    {
        return new static("CMS name was not specified in asset [$asset].");
    }
}
