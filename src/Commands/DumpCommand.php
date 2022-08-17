<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class DumpCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump-autoload the specified module or for all module.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Generating optimized autoload modules.');

        if ($cms = $this->argument('cms')) {
            $this->dump($cms);
        } else {
            foreach ($this->laravel['cmss']->all() as $cms) {
                $this->dump($cms->getStudlyName());
            }
        }

        return 0;
    }

    public function dump($cms)
    {
        $cms = $this->laravel['cmss']->findOrFail($cms);

        $this->line("<comment>Running for module</comment>: {$cms}");

        chdir($cms->getPath());

        passthru('composer dump -o -n -q');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['cms', InputArgument::OPTIONAL, 'CMS name.'],
        ];
    }
}
