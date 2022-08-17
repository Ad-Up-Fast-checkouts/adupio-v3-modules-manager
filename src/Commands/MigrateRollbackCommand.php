<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use AdUpFastcheckouts\adupiov3modulesmanager\Migrations\Migrator;
use AdUpFastcheckouts\adupiov3modulesmanager\Traits\MigrationLoaderTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateRollbackCommand extends Command
{
    use MigrationLoaderTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:migrate-rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback the modules migrations.';

    /**
     * @var \AdUpFastcheckouts\adupiov3modulesmanager\Contracts\RepositoryInterface
     */
    protected $cms;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->cms = $this->laravel['cmss'];

        $name = $this->argument('cms');

        if (!empty($name)) {
            $this->rollback($name);

            return 0;
        }

        foreach ($this->cms->getOrdered($this->option('direction')) as $cms) {
            $this->line('Running for module: <info>' . $cms->getName() . '</info>');

            $this->rollback($cms);
        }

        return 0;
    }

    /**
     * Rollback migration from the specified module.
     *
     * @param $cms
     */
    public function rollback($cms)
    {
        if (is_string($cms)) {
            $cms = $this->cms->findOrFail($cms);
        }

        $migrator = new Migrator($cms, $this->getLaravel());

        $database = $this->option('database');

        if (!empty($database)) {
            $migrator->setDatabase($database);
        }

        $migrated = $migrator->rollback();

        if (count($migrated)) {
            foreach ($migrated as $migration) {
                $this->line("Rollback: <info>{$migration}</info>");
            }

            return;
        }

        $this->comment('Nothing to rollback.');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['cms', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'desc'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
        ];
    }
}
