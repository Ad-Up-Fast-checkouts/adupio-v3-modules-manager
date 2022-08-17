<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use AdUpFastcheckouts\adupiov3modulesmanager\Migrations\Migrator;
use AdUpFastcheckouts\adupiov3modulesmanager\Publishing\MigrationPublisher;
use Symfony\Component\Console\Input\InputArgument;

class PublishMigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:publish-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a module's migrations to the application";

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($name = $this->argument('cms')) {
            $cms = $this->laravel['cmss']->findOrFail($name);

            $this->publish($cms);

            return 0;
        }

        foreach ($this->laravel['cmss']->allEnabled() as $cms) {
            $this->publish($cms);
        }

        return 0;
    }

    /**
     * Publish migration for the specified module.
     *
     * @param \AdUpFastcheckouts\adupiov3modulesmanager\CMS $cms
     */
    public function publish($cms)
    {
        with(new MigrationPublisher(new Migrator($cms, $this->getLaravel())))
            ->setRepository($this->laravel['cmss'])
            ->setConsole($this)
            ->publish();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['cms', InputArgument::OPTIONAL, 'The name of module being used.'],
        ];
    }
}
