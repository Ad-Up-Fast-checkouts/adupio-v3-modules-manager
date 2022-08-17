<?php

namespace AdUpFastcheckouts\adupiov3modulesmanager\Contracts;

use AdUpFastcheckouts\adupiov3modulesmanager\CMS;

interface ActivatorInterface
{
    /**
     * Enables a module
     *
     * @param CMS $module
     */
    public function enable(CMS $module): void;

    /**
     * Disables a module
     *
     * @param CMS $module
     */
    public function disable(CMS $module): void;

    /**
     * Determine whether the given status same with a module status.
     *
     * @param CMS $module
     * @param bool $status
     *
     * @return bool
     */
    public function hasStatus(CMS $module, bool $status): bool;

    /**
     * Set active state for a module.
     *
     * @param CMS $module
     * @param bool $active
     */
    public function setActive(CMS $module, bool $active): void;

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
     * @param  CMS $module
     */
    public function delete(CMS $module): void;

    /**
     * Deletes any module activation statuses created by this class.
     */
    public function reset(): void;
}
