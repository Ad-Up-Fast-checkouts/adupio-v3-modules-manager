<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use AdUpFastcheckouts\adupiov3modulesmanager\CMS;
use Symfony\Component\Console\Input\InputArgument;

class DisableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable the specified module.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        /**
         * check if user entred an argument
         */
        if ($this->argument('cms') === null) {
            $this->disableAll();
        }

        /** @var CMS $cms */
        $cms = $this->laravel['cmss']->findOrFail($this->argument('cms'));

        if ($cms->isEnabled()) {
            $cms->disable();

            $this->info("CMS [{$cms}] disabled successful.");
        } else {
            $this->comment("CMS [{$cms}] has already disabled.");
        }

        return 0;
    }

    /**
     * disableAll
     *
     * @return void
     */
    public function disableAll()
    {
        /** @var CMSs $cmss */
        $cmss = $this->laravel['cmss']->all();

        foreach ($cmss as $cms) {
            if ($cms->isEnabled()) {
                $cms->disable();

                $this->info("CMS [{$cms}] disabled successful.");
            } else {
                $this->comment("CMS [{$cms}] has already disabled.");
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
