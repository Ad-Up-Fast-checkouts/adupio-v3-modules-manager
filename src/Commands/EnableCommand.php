<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use AdUpFastcheckouts\adupiov3modulesmanager\CMS;
use Symfony\Component\Console\Input\InputArgument;

class EnableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:enable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable the specified module.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        /**
         * check if user entred an argument
         */
        if ($this->argument('cms') === null) {
            $this->enableAll();

            return 0;
        }

        /** @var CMS $cms */
        $cms = $this->laravel['cmss']->findOrFail($this->argument('cms'));

        if ($cms->isDisabled()) {
            $cms->enable();

            $this->info("CMS [{$cms}] enabled successful.");
        } else {
            $this->comment("CMS [{$cms}] has already enabled.");
        }

        return 0;
    }

    /**
     * enableAll
     *
     * @return void
     */
    public function enableAll()
    {
        /** @var CMSs $cmss */
        $cmss = $this->laravel['cmss']->all();

        foreach ($cmss as $cms) {
            if ($cms->isDisabled()) {
                $cms->enable();

                $this->info("CMS [{$cms}] enabled successful.");
            } else {
                $this->comment("CMS [{$cms}] has already enabled.");
            }
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
            ['cms', InputArgument::OPTIONAL, 'CMS name.'],
        ];
    }
}
