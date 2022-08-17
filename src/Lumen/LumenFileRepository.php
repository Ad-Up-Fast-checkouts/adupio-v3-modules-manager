<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Lumen;

use AdUpFastcheckouts\adupiov3modulesmanager\FileRepository;

class LumenFileRepository extends FileRepository
{
    /**
     * {@inheritdoc}
     */
    protected function createModule(...$args)
    {
        return new Module(...$args);
    }
}
