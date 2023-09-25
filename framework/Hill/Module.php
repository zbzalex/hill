<?php

namespace Hill;

/**
 * Module class that store all module meta information
 */
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
     * @param string $moduleClass   Module class
     * @param array  $config        Module config
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

    /**
     * 
     */
    public function getGuards()
    {
        return $this->guards;
    }

    /**
     * 
     */
    public function getPipes()
    {
        return $this->pipes;
    }

    /**
     * 
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * @return InstanceWrappers[]
     */
    public function getInterceptors()
    {
        return $this->interceptors;
    }

    /**
     * @param string $instanceClass
     * 
     * @return InstanceWrapper
     */
    public function addController($instanceClass)
    {
        $wrapper = new InstanceWrapper($instanceClass);
        $this->controllers[$instanceClass] = $wrapper;

        return $wrapper;
    }

    /**
     * @param string $instanceClass
     * @param mixed $factory
     * 
     * @return InstanceWrapper
     */
    public function addProvider($instanceClass, $factory = null)
    {
        $wrapper = new InstanceWrapper($instanceClass, $factory);
        $this->providers[$instanceClass] = $wrapper;

        return $wrapper;
    }

    /**
     * @param string $instanceClass
     * 
     * @return InstanceWrapper
     */
    public function addGuard($instanceClass)
    {
        $wrapper = new InstanceWrapper($instanceClass);
        $this->guards[$instanceClass] = $wrapper;

        return $wrapper;
    }

    /**
     * @param string $instanceClass
     * 
     * @return InstanceWrapper
     */
    public function addPipe($instanceClass)
    {
        $wrapper = new InstanceWrapper($instanceClass);
        $this->pipes[$instanceClass] = $wrapper;

        return $wrapper;
    }

    /**
     * @param stirng $instanceClass
     * 
     * @return InstanceWrapper
     */
    public function addMiddleware($instanceClass)
    {
        $wrapper = new InstanceWrapper($instanceClass);
        $this->middlewares[$instanceClass] = $wrapper;

        return $wrapper;
    }

    /**
     * @param string $instanceClass
     * 
     * @return InstanceWrapper
     */
    public function addInterceptor($instanceClass)
    {
        $wrapper = new InstanceWrapper($instanceClass);
        $this->interceptors[$instanceClass] = $wrapper;

        return $wrapper;
    }

    /**
     * @param Module $module
     */
    public function addRelatedModule(Module $module)
    {
        $this->relatedModules[$module->getModuleClass()] = $module;
    }

    /**
     * @param string $providerClass
     * 
     * @return object|null
     */
    public function get($providerClass)
    {
        return isset($this->providers[$providerClass])
            ? $this->providers[$providerClass]->instance
            : null;
    }
}
