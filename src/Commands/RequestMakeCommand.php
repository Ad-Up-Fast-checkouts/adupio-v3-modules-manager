<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Support\Str;
use AdUpFastcheckouts\adupiov3modulesmanager\Support\Config\GenerateConfigReader;
use AdUpFastcheckouts\adupiov3modulesmanager\Support\Stub;
use AdUpFastcheckouts\adupiov3modulesmanager\Traits\CMSCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class RequestMakeCommand extends GeneratorCommand
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
    protected $name = 'module:make-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class for the specified module.';

    public function getDefaultNamespace(): string
    {
        $cms = $this->laravel['modules'];

        return $cms->config('paths.generator.request.namespace') ?: $cms->config('paths.generator.request.path', 'Http/Requests');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the form request class.'],
            ['cms', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $cms = $this->laravel['modules']->findOrFail($this->getCMSName());

        return (new Stub('/request.stub', [
            'NAMESPACE' => $this->getClassNamespace($cms),
            'CLASS'     => $this->getClass(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getCMSPath($this->getCMSName());

        $requestPath = GenerateConfigReader::read('request');

        return $path . $requestPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }
}
