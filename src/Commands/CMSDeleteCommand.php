<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class CMSDeleteCommand extends Command
{
    protected $name = 'cms:delete';
    protected $description = 'Delete a module from the application';

    public function handle(): int
    {
        $this->laravel['cmss']->delete($this->argument('cms'));

        $this->info("CMS {$this->argument('cms')} has been deleted.");

        return 0;
    }

    protected function getArguments()
    {
        return [
            ['cms', InputArgument::REQUIRED, 'The name of module to delete.'],
        ];
    }
}
