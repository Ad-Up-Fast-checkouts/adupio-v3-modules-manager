<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class UseCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:use';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use the specified module.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cms = Str::studly($this->argument('cms'));

        if (!$this->laravel['cmss']->has($cms)) {
            $this->error("CMS [{$cms}] does not exists.");

            return E_ERROR;
        }

        $this->laravel['cmss']->setUsed($cms);

        $this->info("CMS [{$cms}] used successfully.");

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
            ['cms', InputArgument::REQUIRED, 'The name of module will be used.'],
        ];
    }
}
