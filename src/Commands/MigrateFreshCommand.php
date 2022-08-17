<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use AdUpFastcheckouts\adupiov3modulesmanager\Traits\CMSCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateFreshCommand extends Command
{
    use CMSCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:migrate-fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all database tables and re-run all migrations';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cms = $this->argument('cms');

        if ($cms && !$this->getCMSName()) {
            $this->error("CMS [$cms] does not exists.");

            return E_ERROR;
        }

        $this->call('migrate:fresh');

        $this->call('module:migrate', [
            'cms' => $this->getCMSName(),
            '--database' => $this->option('database'),
            '--force' => $this->option('force'),
            '--seed' => $this->option('seed'),
        ]);

        return 0;
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
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],
        ];
    }

    public function getCMSName()
    {
        $cms = $this->argument('cms');

        if (!$cms) {
            return null;
        }

        $cms = app('cmss')->find($cms);

        return $cms ? $cms->getStudlyName() : null;
    }
}
