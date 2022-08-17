<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Process;

use AdUpFastcheckouts\adupiov3modulesmanager\CMS;

class Updater extends Runner
{
    /**
     * Update the dependencies for the specified module by given the module name.
     *
     * @param string $cms
     */
    public function update($cms)
    {
        $cms = $this->cms->findOrFail($cms);

        chdir(base_path());

        $this->installRequires($cms);
        $this->installDevRequires($cms);
        $this->copyScriptsToMainComposerJson($cms);
    }

    /**
     * Check if composer should output anything.
     *
     * @return string
     */
    private function isComposerSilenced()
    {
        return config('modules.composer.composer-output') === false ? ' --quiet' : '';
    }

    /**
     * @param CMS $cms
     */
    private function installRequires(CMS $cms)
    {
        $packages = $cms->getComposerAttr('require', []);

        $concatenatedPackages = '';
        foreach ($packages as $name => $version) {
            $concatenatedPackages .= "\"{$name}:{$version}\" ";
        }

        if (!empty($concatenatedPackages)) {
            $this->run("composer require {$concatenatedPackages}{$this->isComposerSilenced()}");
        }
    }

    /**
     * @param CMS $cms
     */
    private function installDevRequires(CMS $cms)
    {
        $devPackages = $cms->getComposerAttr('require-dev', []);

        $concatenatedPackages = '';
        foreach ($devPackages as $name => $version) {
            $concatenatedPackages .= "\"{$name}:{$version}\" ";
        }

        if (!empty($concatenatedPackages)) {
            $this->run("composer require --dev {$concatenatedPackages}{$this->isComposerSilenced()}");
        }
    }

    /**
     * @param CMS $cms
     */
    private function copyScriptsToMainComposerJson(CMS $cms)
    {
        $scripts = $cms->getComposerAttr('scripts', []);

        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        foreach ($scripts as $key => $script) {
            if (array_key_exists($key, $composer['scripts'])) {
                $composer['scripts'][$key] = array_unique(array_merge($composer['scripts'][$key], $script));

                continue;
            }
            $composer['scripts'] = array_merge($composer['scripts'], [$key => $script]);
        }

        file_put_contents(base_path('composer.json'), json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
}
