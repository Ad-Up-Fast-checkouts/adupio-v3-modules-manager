<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Lumen;

use AdUpFastcheckouts\adupiov3modulesmanager\FileRepository;

class LumenFileRepository extends FileRepository
{
    /**
     * {@inheritdoc}
     */
    protected function createCMS(...$args)
    {
        return new CMS(...$args);
    }
}
