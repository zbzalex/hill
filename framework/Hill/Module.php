<?php

namespace Hill;

//
//
//
class Module
{
    private $moduleClass;
    private $relatedModules;
    private $controllers;
    private $providers;
    private $unresolvedInstances;

    /**
     * 
     */
    public function __construct($moduleClass)
    {
        $this->moduleClass = $moduleClass;
        $this->relatedModules = [];
        $this->controllers = [];
        $this->providers = [];
        $this->unresolvedInstances = [];
    }

    /**
     * @return string
     */
    public function getModuleClass()
    {
        return $this->moduleClass;
    }

    /**
     * @return Module[]
     */
    public function getRelatedModules()
    {
        return $this->relatedModules;
    }

    /**
     * @return InstanceWrapper[]
     */
    public function getControllers()
    {
        return $this->controllers;
    }

    /**
     * @return InstanceWrapper[]
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * 
     */
    public function getUnresolvedInstances()
    {
        return $this->unresolvedInstances;
    }

    /**
     * 
     */
    public function addController($instanceClass)
    {
        $this->controllers[$instanceClass] =
            new InstanceWrapper($instanceClass);
    }

    /**
     * 
     */
    public function addProvider($instanceClass)
    {
        $this->providers[$instanceClass] =
            new InstanceWrapper($instanceClass);
    }

    /**
     * 
     */
    public function addUnresolvedInstance($instanceClass)
    {
        $this->unresolvedInstances[$instanceClass] =
            new InstanceWrapper($instanceClass);
    }
    
    /**
     * 
     */
    public function addRelatedModule(Module $module)
    {
        $this->relatedModules[$module->getModuleClass()] = $module;
    }
}
