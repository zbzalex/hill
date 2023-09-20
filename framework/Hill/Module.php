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
    private $config;
    private $guards;
    private $pipes;
    private $middlewares;
    private $interceptors;

    /**
     * @param string $moduleClass
     * @param array $config
     */
    public function __construct($moduleClass, array $config = [])
    {
        $this->moduleClass = $moduleClass;
        $this->relatedModules = [];
        $this->controllers = [];
        $this->providers = [];
        $this->config = $config;
        $this->guards = [];
        $this->pipes = [];
        $this->middlewares = [];
        $this->interceptors = [];
    }

    /**
     * @return string
     */
    public function getModuleClass()
    {
        return $this->moduleClass;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
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

    public function getGuards() {
        return $this->guards;
    }

    public function getPipes() {
        return $this->pipes;
    }

    public function getMiddlewares() {
        return $this->middlewares;
    }

    /**
     * 
     */
    public function getInterceptors()
    {
        return $this->interceptors;
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
    public function addProvider($instanceClass, $factory = null)
    {
        $this->providers[$instanceClass] =
            new InstanceWrapper($instanceClass, $factory);
    }
    
    /**
     * 
     */
    public function addGuard($instanceClass)
    {
        $this->guards[$instanceClass] =
            new InstanceWrapper($instanceClass);
    }

    public function addPipe($instanceClass)
    {
        $this->pipes[$instanceClass] =
            new InstanceWrapper($instanceClass);
    }

    public function addMiddleware($instanceClass)
    {
        $this->middlewares[$instanceClass] =
            new InstanceWrapper($instanceClass);
    }

    public function addInterceptor($instanceClass)
    {
        $this->interceptors[$instanceClass] =
            new InstanceWrapper($instanceClass);
    }

    /**
     * 
     */
    public function addRelatedModule(Module $module)
    {
        $this->relatedModules[$module->getModuleClass()] = $module;
    }
    
    public function get($providerClass)
    {
        return isset($this->providers[$providerClass])
            ? $this->providers[$providerClass]->instance
            : null;
    }
}
