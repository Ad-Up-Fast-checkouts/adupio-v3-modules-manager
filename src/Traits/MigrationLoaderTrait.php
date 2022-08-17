<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Traits;

trait MigrationLoaderTrait
{
    /**
     * Include all migrations files from the specified module.
     *
     * @param string $cms
     */
    protected function loadMigrationFiles($cms)
    {
        $path = $this->laravel['modules']->getCMSPath($cms) . $this->getMigrationGeneratorPath();

        $files = $this->laravel['files']->glob($path . '/*_*.php');

        foreach ($files as $file) {
            $this->laravel['files']->requireOnce($file);
        }
    }

    /**
     * Get migration generator path.
     *
     * @return string
     */
    protected function getMigrationGeneratorPath()
    {
        return $this->laravel['modules']->config('paths.generator.migration');
    }
}
