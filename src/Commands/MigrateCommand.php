<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use AdUpFastcheckouts\adupiov3modulesmanager\Migrations\Migrator;
use AdUpFastcheckouts\adupiov3modulesmanager\CMS;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the migrations from the specified module or from all modules.';

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

            $this->migrate($cms);

            return 0;
        }

        foreach ($this->cms->getOrdered($this->option('direction')) as $cms) {
            $this->line('Running for module: <info>' . $cms->getName() . '</info>');

            $this->migrate($cms);
        }

        return 0;
    }

    /**
     * Run the migration from the specified module.
     *
     * @param CMS $cms
     */
    protected function migrate(CMS $cms)
    {
        $path = str_replace(base_path(), '', (new Migrator($cms, $this->getLaravel()))->getPath());

        if ($this->option('subpath')) {
            $path = $path . "/" . $this->option("subpath");
        }

        $this->call('migrate', [
            '--path' => $path,
            '--database' => $this->option('database'),
            '--pretend' => $this->option('pretend'),
            '--force' => $this->option('force'),
        ]);

        if ($this->option('seed')) {
            $this->call('cms:seed', ['cms' => $cms->getName(), '--force' => $this->option('force')]);
        }
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
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],
            ['subpath', null, InputOption::VALUE_OPTIONAL, 'Indicate a subpath to run your migrations from'],
        ];
    }
}
