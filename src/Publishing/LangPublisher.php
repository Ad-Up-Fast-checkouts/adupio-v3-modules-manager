<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Publishing;

use AdUpFastcheckouts\adupiov3modulesmanager\Support\Config\GenerateConfigReader;

class LangPublisher extends Publisher
{
    /**
     * Determine whether the result message will shown in the console.
     *
     * @var bool
     */
    protected $showMessage = false;

    /**
     * Get destination path.
     *
     * @return string
     */
    public function getDestinationPath()
    {
        $name = $this->cms->getLowerName();

        return base_path("resources/lang/{$name}");
    }

    /**
     * Get source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->getCMS()->getExtraPath(
            GenerateConfigReader::read('lang')->getPath()
        );
    }
}
