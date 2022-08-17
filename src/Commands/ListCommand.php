<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Commands;

use Illuminate\Console\Command;
use AdUpFastcheckouts\adupiov3modulesmanager\CMS;
use Symfony\Component\Console\Input\InputOption;

class ListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show list of all modules.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->table(['Name', 'Status', 'Priority', 'Path'], $this->getRows());

        return 0;
    }

    /**
     * Get table rows.
     *
     * @return array
     */
    public function getRows()
    {
        $rows = [];

        /** @var CMS $cms */
        foreach ($this->getCMSs() as $cms) {
            $rows[] = [
                $cms->getName(),
                $cms->isEnabled() ? 'Enabled' : 'Disabled',
                $cms->get('priority'),
                $cms->getPath(),
            ];
        }

        return $rows;
    }

    public function getCMSs()
    {
        switch ($this->option('only')) {
            case 'enabled':
                return $this->laravel['cmss']->getByStatus(1);

                break;

            case 'disabled':
                return $this->laravel['cmss']->getByStatus(0);

                break;

            case 'priority':
                return $this->laravel['cmss']->getPriority($this->option('direction'));

                break;

            default:
                return $this->laravel['cmss']->all();

                break;
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['only', 'o', InputOption::VALUE_OPTIONAL, 'Types of modules will be displayed.', null],
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
        ];
    }
}
