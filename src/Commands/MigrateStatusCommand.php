<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use AdUpFastcheckouts\adupiov3modulesmanager\Migrations\Migrator;
use AdUpFastcheckouts\adupiov3modulesmanager\CMS;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateStatusCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:migrate-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Status for all module migrations';

    /**
     * @var \AdUpFastcheckouts\adupiov3modulesmanager\Contracts\RepositoryInterface
     */
    protected $cms;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): int
    {
        $this->cms = $this->laravel['cmss'];

        $name = $this->argument('cms');

        if ($name) {
            $cms = $this->cms->findOrFail($name);

            $this->migrateStatus($cms);

            return 0;
        }

        foreach ($this->cms->getOrdered($this->option('direction')) as $cms) {
            $this->line('Running for module: <info>' . $cms->getName() . '</info>');
            $this->migrateStatus($cms);
        }

        return 0;
    }

    /**
     * Run the migration from the specified module.
     *
     * @param CMS $cms
     */
    protected function migrateStatus(CMS $cms)
    {
        $path = str_replace(base_path(), '', (new Migrator($cms, $this->getLaravel()))->getPath());

        $this->call('migrate:status', [
            '--path' => $path,
            '--database' => $this->option('database'),
        ]);
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
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
        ];
    }
}
