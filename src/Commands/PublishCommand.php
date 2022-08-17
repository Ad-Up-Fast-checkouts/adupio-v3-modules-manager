<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use AdUpFastcheckouts\adupiov3modulesmanager\CMS;
use AdUpFastcheckouts\adupiov3modulesmanager\Publishing\AssetPublisher;
use Symfony\Component\Console\Input\InputArgument;

class PublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a module\'s assets to the application';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($name = $this->argument('cms')) {
            $this->publish($name);

            return 0;
        }

        $this->publishAll();

        return 0;
    }

    /**
     * Publish assets from all modules.
     */
    public function publishAll()
    {
        foreach ($this->laravel['cmss']->allEnabled() as $cms) {
            $this->publish($cms);
        }
    }

    /**
     * Publish assets from the specified module.
     *
     * @param string $name
     */
    public function publish($name)
    {
        if ($name instanceof CMS) {
            $cms = $name;
        } else {
            $cms = $this->laravel['cmss']->findOrFail($name);
        }

        with(new AssetPublisher($cms))
            ->setRepository($this->laravel['cmss'])
            ->setConsole($this)
            ->publish();

        $this->line("<info>Published</info>: {$cms->getStudlyName()}");
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
}
