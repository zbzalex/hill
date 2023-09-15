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
    public function addModule($moduleClass, array $config = [])
    {
        try {
            $reflectionClass = new \ReflectionClass($moduleClass);
            if ($reflectionClass->implementsInterface(IModule::class)) {
                $module = new Module($moduleClass, $config);

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
     * @return Module
     */
    public function get($moduleClass)
    {
        return isset($this->modules[$moduleClass]) ? $this->modules[$moduleClass] : null;
    }
}
