<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Laravel;

use AdUpFastcheckouts\adupiov3modulesmanager\FileRepository;

class LaravelFileRepository extends FileRepository
{
    /**
     * {@inheritdoc}
     */
    protected function createCMS(...$args)
    {
        return new CMS(...$args);
    }
}
