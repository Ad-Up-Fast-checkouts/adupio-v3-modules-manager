<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Publishing;

use AdUpFastcheckouts\adupiov3modulesmanager\Migrations\Migrator;

class MigrationPublisher extends AssetPublisher
{
    /**
     * @var Migrator
     */
    private $migrator;

    /**
     * MigrationPublisher constructor.
     * @param Migrator $migrator
     */
    public function __construct(Migrator $migrator)
    {
        $this->migrator = $migrator;
        parent::__construct($migrator->getCMS());
    }

    /**
     * Get destination path.
     *
     * @return string
     */
    public function getDestinationPath()
    {
        return $this->repository->config('paths.migration');
    }

    /**
     * Get source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->migrator->getPath();
    }
}
