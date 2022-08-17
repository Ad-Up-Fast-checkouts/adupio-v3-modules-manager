<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use AdUpFastcheckouts\adupiov3modulesmanager\Traits\CMSCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateRefreshCommand extends Command
{
    use CMSCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:migrate-refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback & re-migrate the modules migrations.';

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

        $this->call('cms:migrate-reset', [
            'cms' => $this->getCMSName(),
            '--database' => $this->option('database'),
            '--force' => $this->option('force'),
        ]);

        $this->call('cms:migrate', [
            'cms' => $this->getCMSName(),
            '--database' => $this->option('database'),
            '--force' => $this->option('force'),
        ]);

        if ($this->option('seed')) {
            $this->call('cms:seed', [
                'cms' => $this->getCMSName(),
            ]);
        }

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
