<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class PublishConfigurationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:publish-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a module\'s config files to the application';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($cms = $this->argument('cms')) {
            $this->publishConfiguration($cms);

            return 0;
        }

        foreach ($this->laravel['modules']->allEnabled() as $cms) {
            $this->publishConfiguration($cms->getName());
        }

        return 0;
    }

    /**
     * @param string $cms
     * @return string
     */
    private function getServiceProviderForCMS($cms)
    {
        $namespace = $this->laravel['config']->get('modules.namespace');
        $studlyName = Str::studly($cms);

        return "$namespace\\$studlyName\\Providers\\{$studlyName}ServiceProvider";
    }

    /**
     * @param string $cms
     */
    private function publishConfiguration($cms)
    {
        $this->call('vendor:publish', [
            '--provider' => $this->getServiceProviderForCMS($cms),
            '--force' => $this->option('force'),
            '--tag' => ['config'],
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
            ['cms', InputArgument::OPTIONAL, 'The name of module being used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['--force', '-f', InputOption::VALUE_NONE, 'Force the publishing of config files'],
        ];
    }
}
