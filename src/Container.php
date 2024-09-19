<?php

namespace Neon;

class Container
{
  /**
   * @var Module[]
   */
  private $modules;

  public function __construct()
  {
    $this->modules = [];
  }

  public function addModule(Module $module)
  {
    $this->modules[$module->getModuleClass()] = $module;
  }

  public function getModules()
  {
    return $this->modules;
  }
  
  public function getModule($moduleClass)
  {
    return isset($this->modules[$moduleClass])
      ? $this->modules[$moduleClass]
      : null;
  }
}
