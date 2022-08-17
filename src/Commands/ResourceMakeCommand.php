<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Support\Str;
use AdUpFastcheckouts\adupiov3modulesmanager\Support\Config\GenerateConfigReader;
use AdUpFastcheckouts\adupiov3modulesmanager\Support\Stub;
use AdUpFastcheckouts\adupiov3modulesmanager\Traits\CMSCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ResourceMakeCommand extends GeneratorCommand
{
    use CMSCommandTrait;

    protected $argumentName = 'name';
    protected $name = 'module:make-resource';
    protected $description = 'Create a new resource class for the specified module.';

    public function getDefaultNamespace(): string
    {
        $cms = $this->laravel['cmss'];

        return $cms->config('paths.generator.resource.namespace') ?: $cms->config('paths.generator.resource.path', 'Transformers');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the resource class.'],
            ['cms', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['collection', 'c', InputOption::VALUE_NONE, 'Create a resource collection.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $cms = $this->laravel['cmss']->findOrFail($this->getCMSName());

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace($cms),
            'CLASS'     => $this->getClass(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['cmss']->getCMSPath($this->getCMSName());

        $resourcePath = GenerateConfigReader::read('resource');

        return $path . $resourcePath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Determine if the command is generating a resource collection.
     *
     * @return bool
     */
    protected function collection(): bool
    {
        return $this->option('collection') ||
            Str::endsWith($this->argument('name'), 'Collection');
    }

    /**
     * @return string
     */
    protected function getStubName(): string
    {
        if ($this->collection()) {
            return '/resource-collection.stub';
        }

        return '/resource.stub';
    }
}
