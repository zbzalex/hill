<?php

namespace Hill;

use ArrayObject;

/**
 * Module class that store all module meta information
 */
class Module extends ArrayObject
{
    /**
     * @var string $moduleClass Module class
     */
    private $moduleClass;

    /**
     * @var
     */
    private $imports;

    /**
     * @var
     */
    private $controllers;

    /**
     * @var
     */
    private $providers;

    /**
     * @var
     */
    private $config;

    /**
     * @var
     */
    private $middlewares;

    /**
     * @var
     */
    private $interceptors;

    /**
     * Contructor
     * 
     * @param string $moduleClass   The module class
     * @param array  $config        The module config
     */
    public function __construct($moduleClass, array $config = [])
    {
        $this->moduleClass = $moduleClass;
        $this->imports = [];
        $this->controllers = [];
        $this->providers = [];
        $this->config = $config;
        $this->middlewares = [];
        $this->interceptors = [];
    }

    /**
     * Returns module class
     * 
     * @return string
     */
    public function getModuleClass()
    {
        return $this->moduleClass;
    }

    /**
     * Returns module config
     * 
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Returns module imports
     * 
     * @return Module[]
     */
    public function getImports()
    {
        return $this->imports;
    }

    /**
     * Returns module controllers
     * 
     * @return InstanceWrapper[]
     */
    public function getControllers()
    {
        return $this->controllers;
    }

    /**
     * Returns module providers
     * 
     * @return InstanceWrapper[]
     */
    public function getProviders()
    {
        return $this->providers;
    }
    
    /**
     * @return InstanceWrappers[]
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
    public function addImport(Module $module)
    {
        $this->imports[$module->getModuleClass()] = $module;
    }
    
    /**
     * @param string $providerClass
     * 
     * @return object|null
     */
    public function offsetGet($providerClass)
    {
        return isset($this->providers[$providerClass])
            ? $this->providers[$providerClass]->instance
            : null;
    }

    /**
     * Check is module global
     * 
     * @return bool
     */
    public function isGlobal()
    {
        return isset($this->config['global']) && $this->config['global'] === true;
    }
}
