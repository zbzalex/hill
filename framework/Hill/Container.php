<?php

namespace Hill;

/**
 * Module container class
 */
class Container
{
    /** @var Module[] $modules */
    private $modules;

    /** @var Module[] $globalModules */
    private $globalModules;

    const MODULE_SCOPE_NULL     = 0x00;
    const MODULE_SCOPE_GLOBAL   = 0x01;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->modules = [];
        $this->globalModules = [];
    }

    /**
     * Register module
     * 
     * @param Module $module The module
     */
    public function registerModule(Module $module, $scope = self::MODULE_SCOPE_NULL)
    {
        if ($scope === static::MODULE_SCOPE_GLOBAL) {
            $this->globalModules[$module->getModuleClass()] = $module;
        } else {
            $this->modules[$module->getModuleClass()] = $module;
        }
    }

    /**
     * Returns registered modules
     * 
     * @return Module[]
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Returns global modules
     * 
     * @return Module[]
     */
    public function getGlobalModules()
    {
        return $this->globalModules;
    }

    /**
     * Returns module by module class
     * 
     * @param string $moduleClass Module class
     * 
     * @return Module
     */
    public function getModule($moduleClass)
    {
        return isset($this->modules[$moduleClass])
            ? $this->modules[$moduleClass]
            : (isset($this->globalModules[$moduleClass])
                ? $this->globalModules[$moduleClass]
                : null);
    }

    /**
     * Checks is module was registered
     * 
     * @param string $moduelClass Module class
     * 
     * @return bool
     */
    public function isModuleRegistered($moduleClass)
    {
        return isset($this->modules[$moduleClass]) || isset($this->globalModules[$moduleClass]);
    }
}
