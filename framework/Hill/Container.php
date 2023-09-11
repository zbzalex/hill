<?php

namespace Hill;

//
// Контейнер.
//
class Container
{
    /**
     * @var array
     */
    private $modules;

    /**
     * 
     */
    public function __construct()
    {
        $this->modules = [];
    }

    /**
     * 
     */
    public function addModule($moduleClass)
    {
        try {
            $reflectionClass = new \ReflectionClass($moduleClass);
            if ($reflectionClass->implementsInterface(IModule::class)) {
                $module = new Module($moduleClass);

                $this->modules[$moduleClass] = $module;

                return $module;
            }
        } catch (\ReflectionException $e) {
        }

        return null;
    }

    /**
     * @return Module[]
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * 
     */
    public function get($moduleClass)
    {
        return isset($this->modules[$moduleClass]) ? $this->modules[$moduleClass] : null;
    }
}
