<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Publishing;

use AdUpFastcheckouts\adupiov3modulesmanager\Support\Config\GenerateConfigReader;

class AssetPublisher extends Publisher
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
        return $this->repository->assetPath($this->cms->getLowerName());
    }

    /**
     * Get source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->getCMS()->getExtraPath(
            GenerateConfigReader::read('assets')->getPath()
        );
    }
}
