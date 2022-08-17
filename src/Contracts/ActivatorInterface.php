<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Contracts;

use AdUpFastcheckouts\adupiov3modulesmanager\CMS;

interface ActivatorInterface
{
    /**
     * Enables a module
     *
     * @param CMS $cms
     */
    public function enable(CMS $cms): void;

    /**
     * Disables a module
     *
     * @param CMS $cms
     */
    public function disable(CMS $cms): void;

    /**
     * Determine whether the given status same with a module status.
     *
     * @param CMS $cms
     * @param bool $status
     *
     * @return bool
     */
    public function hasStatus(CMS $cms, bool $status): bool;

    /**
     * Set active state for a module.
     *
     * @param CMS $cms
     * @param bool $active
     */
    public function setActive(CMS $cms, bool $active): void;

    /**
     * Sets a module status by its name
     *
     * @param  string $name
     * @param  bool $active
     */
    public function setActiveByName(string $name, bool $active): void;

    /**
     * Deletes a module activation status
     *
     * @param  CMS $cms
     */
    public function delete(CMS $cms): void;

    /**
     * Deletes any module activation statuses created by this class.
     */
    public function reset(): void;
}
