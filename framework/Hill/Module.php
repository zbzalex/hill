<?php

namespace Hill;

use ArrayObject;

/**
 * Module class that store all module meta information
 */
class Module extends ArrayObject
{
    private string $moduleClass;
    private $imports;
    private $controllers;
    private $providers;
    private array $config;
    private $middlewares;
    private $interceptors;

    /**
     * @param string $moduleClass   Module class
     * @param array  $config        Module config
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
    public function getImports()
    {
        return $this->imports;
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
     * Check if module is global
     * 
     * @return bool
     */
    public function isGlobal()
    {
        return isset($this->config['global']) && $this->config['global'];
    }
}
