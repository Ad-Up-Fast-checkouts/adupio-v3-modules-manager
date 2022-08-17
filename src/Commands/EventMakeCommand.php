<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Support\Str;
use AdUpFastcheckouts\adupiov3modulesmanager\Support\Config\GenerateConfigReader;
use AdUpFastcheckouts\adupiov3modulesmanager\Support\Stub;
use AdUpFastcheckouts\adupiov3modulesmanager\Traits\CMSCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class EventMakeCommand extends GeneratorCommand
{
    use CMSCommandTrait;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event class for the specified module';

    public function getTemplateContents()
    {
        $cms = $this->laravel['cmss']->findOrFail($this->getCMSName());

        return (new Stub('/event.stub', [
            'NAMESPACE' => $this->getClassNamespace($cms),
            'CLASS' => $this->getClass(),
        ]))->render();
    }

    public function getDestinationFilePath()
    {
        $path       = $this->laravel['cmss']->getCMSPath($this->getCMSName());

        $eventPath = GenerateConfigReader::read('event');

        return $path . $eventPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    public function getDefaultNamespace(): string
    {
        $cms = $this->laravel['cmss'];

        return $cms->config('paths.generator.event.namespace') ?: $cms->config('paths.generator.event.path', 'Events');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the event.'],
            ['cms', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }
}
