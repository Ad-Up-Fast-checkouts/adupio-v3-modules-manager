<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Laravel;

use AdUpFastcheckouts\adupiov3modulesmanager\FileRepository;

class LaravelFileRepository extends FileRepository
{
    /**
     * {@inheritdoc}
     */
    protected function createModule(...$args)
    {
        return new Module(...$args);
    }
}
