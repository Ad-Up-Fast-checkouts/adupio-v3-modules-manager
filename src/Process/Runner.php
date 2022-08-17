<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Process;

use AdUpFastcheckouts\adupiov3modulesmanager\Contracts\RepositoryInterface;
use AdUpFastcheckouts\adupiov3modulesmanager\Contracts\RunableInterface;

class Runner implements RunableInterface
{
    /**
     * The module instance.
     * @var RepositoryInterface
     */
    protected $cms;

    public function __construct(RepositoryInterface $cms)
    {
        $this->cms = $cms;
    }

    /**
     * Run the given command.
     *
     * @param string $command
     */
    public function run($command)
    {
        passthru($command);
    }
}
