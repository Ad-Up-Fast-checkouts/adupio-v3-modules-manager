<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Str;
use AdUpFastcheckouts\adupiov3modulesmanager\Support\Config\GenerateConfigReader;
use AdUpFastcheckouts\adupiov3modulesmanager\Support\Stub;
use AdUpFastcheckouts\adupiov3modulesmanager\Traits\CMSCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class ComponentViewMakeCommand extends GeneratorCommand
{
    use CMSCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:make-component-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new component-view for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the component.'],
            ['cms', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        return (new Stub('/component-view.stub', ['QUOTE'=> Inspiring::quote()]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['cmss']->getCMSPath($this->getCMSName());
        $factoryPath = GenerateConfigReader::read('component-view');

        return $path . $factoryPath->getPath() . '/' . $this->getFileName();
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::lower($this->argument('name')) . '.blade.php';
    }
}
