<?php

namespace Hill;

//
// Контейнер.
//
class Container
{
    /**
     * @var Module[]
     */
    private $modules;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->modules = [];
    }

    /**
     * @param string $moduleClass
     * @param array $config
     * 
     * @return Module|null
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
     * @param string $moduleClass
     * 
     * @return Module
     */
    public function get($moduleClass)
    {
        return isset($this->modules[$moduleClass]) ? $this->modules[$moduleClass] : null;
    }
}
