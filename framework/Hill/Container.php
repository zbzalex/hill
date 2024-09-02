<?php

namespace Hill;

/**
 * Module container class
 */
class Container
{
  /** @var Module[] $modules */
  private $modules;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->modules = [];
  }

  /**
   * Add module
   * 
   * @param Module $module The module
   */
  public function addModule(Module $module)
  {
    $this->modules[$module->getModuleClass()] = $module;
  }

  /**
   * Returns registered modules
   * 
   * @return Module[]
   */
  public function getModules()
  {
    return $this->modules;
  }

  /**
   * Returns module by module class
   * 
   * @param string $moduleClass Module class
   * 
   * @return Module
   */
  public function getModule($moduleClass)
  {
    return isset($this->modules[$moduleClass])
      ? $this->modules[$moduleClass]
      : null;
  }
}
