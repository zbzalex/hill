<?php

namespace Neon;

class Module
{
  /**
   * @var string
   */
  private $moduleClass;

  /**
   * @var string[]
   */
  private $imports;

  /**
   * @var string[]
   */
  private $controllers;

  /**
   * @var mixed[]
   */
  private $providers;

  /**
   * @var array
   */
  private $config;

  public function __construct($moduleClass, array $config = [])
  {
    $this->moduleClass = $moduleClass;
    $this->imports = [];
    $this->controllers = [];
    $this->providers = [];
    $this->config = $config;
  }

  public function getModuleClass()
  {
    return $this->moduleClass;
  }

  public function getConfig()
  {
    return $this->config;
  }

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
   * @return InstancWrapper[]
   */
  public function getProviders()
  {
    return $this->providers;
  }

  public function addController($instanceClass)
  {
    $wrapper = new InstanceWrapper($instanceClass);
    $this->controllers[$instanceClass] = $wrapper;

    return $wrapper;
  }

  public function addProvider($instanceClass, $factory = null)
  {
    $wrapper = new InstanceWrapper($instanceClass, $factory);
    $this->providers[$instanceClass] = $wrapper;

    return $wrapper;
  }
    
  public function addImport(Module $module)
  {
    $this->imports[$module->getModuleClass()] = $module;
  }
}
