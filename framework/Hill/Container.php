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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->modules = [];
        $this->globalModules = [];
    }

    /**
     * @param string $moduleClass
     * @param array $config
     * 
     * @return Module|null
     */
    public function emplaceAndGetModule($moduleClass, array $config = [])
    {
        $module = new Module($moduleClass, $config);

        $this->modules[$moduleClass] = $module;

        return $module;
    }

    public function emplaceAndGetGlobalModule($moduleClass, array $config = [])
    {
        $module = new Module($moduleClass, $config);

        $this->globalModules[$moduleClass] = $module;

        return $module;
    }

    /**
     * @return Module[]
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @return Module[]
     */
    public function getGlobalModules()
    {
        return $this->globalModules;
    }

    /**
     * @param string $moduleClass
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
}
