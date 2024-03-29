<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use AdUpFastcheckouts\adupiov3modulesmanager\Traits\CMSCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class UpdateCommand extends Command
{
    use CMSCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update dependencies for the specified module or for all modules.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('cms');

        if ($name) {
            $this->updateCMS($name);

            return 0;
        }

        /** @var \AdUpFastcheckouts\adupiov3modulesmanager\CMS $cms */
        foreach ($this->laravel['cmss']->getOrdered() as $cms) {
            $this->updateCMS($cms->getName());
        }

        return 0;
    }

    protected function updateCMS($name)
    {
        $this->line('Running for module: <info>' . $name . '</info>');

        $this->laravel['cmss']->update($name);

        $this->info("CMS [{$name}] updated successfully.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['cms', InputArgument::OPTIONAL, 'The name of module will be updated.'],
        ];
    }
}
