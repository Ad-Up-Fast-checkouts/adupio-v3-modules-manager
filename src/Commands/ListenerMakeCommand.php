<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Support\Str;
use AdUpFastcheckouts\adupiov3modulesmanager\CMS;
use AdUpFastcheckouts\adupiov3modulesmanager\Support\Config\GenerateConfigReader;
use AdUpFastcheckouts\adupiov3modulesmanager\Support\Stub;
use AdUpFastcheckouts\adupiov3modulesmanager\Traits\CMSCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ListenerMakeCommand extends GeneratorCommand
{
    use CMSCommandTrait;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event listener class for the specified module';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the command.'],
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
            ['event', 'e', InputOption::VALUE_OPTIONAL, 'The event class being listened for.'],
            ['queued', null, InputOption::VALUE_NONE, 'Indicates the event listener should be queued.'],
        ];
    }

    protected function getTemplateContents()
    {
        $cms = $this->laravel['modules']->findOrFail($this->getCMSName());

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace($cms),
            'EVENTNAME' => $this->getEventName($cms),
            'SHORTEVENTNAME' => $this->getShortEventName(),
            'CLASS' => $this->getClass(),
        ]))->render();
    }

    public function getDefaultNamespace(): string
    {
        $cms = $this->laravel['modules'];

        return $cms->config('paths.generator.listener.namespace') ?: $cms->config('paths.generator.listener.path', 'Listeners');
    }

    protected function getEventName(CMS $cms)
    {
        $namespace = $this->laravel['modules']->config('namespace') . "\\" . $cms->getStudlyName();
        $eventPath = GenerateConfigReader::read('event');

        $eventName = $namespace . "\\" . $eventPath->getPath() . "\\" . $this->option('event');

        return str_replace('/', '\\', $eventName);
    }

    protected function getShortEventName()
    {
        return class_basename($this->option('event'));
    }

    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getCMSPath($this->getCMSName());

        $listenerPath = GenerateConfigReader::read('listener');

        return $path . $listenerPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * @return string
     */
    protected function getStubName(): string
    {
        if ($this->option('queued')) {
            if ($this->option('event')) {
                return '/listener-queued.stub';
            }

            return '/listener-queued-duck.stub';
        }

        if ($this->option('event')) {
            return '/listener.stub';
        }

        return '/listener-duck.stub';
    }
}
