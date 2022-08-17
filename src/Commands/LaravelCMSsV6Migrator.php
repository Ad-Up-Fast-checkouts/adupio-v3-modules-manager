<?php

declare(strict_types=1);

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use AdUpFastcheckouts\adupiov3modulesmanager\Contracts\RepositoryInterface;
use AdUpFastcheckouts\adupiov3modulesmanager\CMS;

class LaravelCMSsV6Migrator extends Command
{
    protected $name = 'module:v6:migrate';
    protected $description = 'Migrate laravel-modules v5 modules statuses to v6.';

    public function handle(): int
    {
        $cmsStatuses = [];
        /** @var RepositoryInterface $cmss */
        $cmss = $this->laravel['modules'];

        $cmss = $cmss->all();
        /** @var CMS $cms */
        foreach ($cmss as $cms) {
            if ($cms->json()->get('active') === 1) {
                $cms->enable();
                $cmsStatuses[] = [$cms->getName(), 'Enabled'];
            }
            if ($cms->json()->get('active') === 0) {
                $cms->disable();
                $cmsStatuses[] = [$cms->getName(), 'Disabled'];
            }
        }
        $this->info('All modules have been migrated.');
        $this->table(['CMS name', 'Status'], $cmsStatuses);

        return 0;
    }
}
