<?php

namespace Hill;

use ArrayObject;

/**
 * Module class that store all module meta information
 */
class Module
{
  /**
   * @var string $moduleClass Module class
   */
  private $moduleClass;

  /**
   * @var string[] $imports Import modules
   */
  private $imports;

  /**
   * @var string[] $controllers Controllers
   */
  private $controllers;

  /**
   * @var mixed[] $providers Providers
   */
  private $providers;

  /**
   * @var array $config Module config
   */
  private $config;

  /**
   * @var IMiddlewares[] $middlewares Module middlewares
   */
  private $middlewares;

  /**
   * @var IInterceptors[] $interceptors Module interceptors
   */
  private $interceptors;

  /**
   * Contructor
   * 
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
   * Returns list of imports
   * 
   * @return Module[]
   */
  public function getImports()
  {
    return $this->imports;
  }

  /**
   * Returns list of controllers
   * 
   * @return InstanceWrapper[]
   */
  public function getControllers()
  {
    return $this->controllers;
  }

  /**
   * Returns list of providers
   * 
   * @return InstanceWrapper[]
   */
  public function getProviders()
  {
    return $this->providers;
  }

  /**
   * Returns list of middlewares
   * 
   * @return InstanceWrappers[]
   */
  public function getMiddlewares()
  {
    return $this->middlewares;
  }

  /**
   * Returns list of interceptors
   * 
   * @return InstanceWrappers[]
   */
  public function getInterceptors()
  {
    return $this->interceptors;
  }

  /**
   * Puts controller in module
   * 
   * @param string $instanceClass Controller class
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
   * Puts provider in module
   * 
   * @param string $instanceClass Provider class
   * @param null|array $factory   Provider factory
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
   * Puts middleware in module
   * 
   * @param stirng $instanceClass Middlewar class
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
   * Puts interceptor in module
   * 
   * @param string $instanceClass Interceptor class
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
   * Puts import in module
   * 
   * @param Module $module Module which will be imported
   */
  public function addImport(Module $module)
  {
    $this->imports[$module->getModuleClass()] = $module;
  }

  /**
   * Array access by key
   * 
   * @param string $providerClass Provider class
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
