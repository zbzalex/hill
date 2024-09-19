<?php

namespace Neon;

class ContainerBuilder
{
  /**
   * @var string|array
   */
  private $moduleConfigOrClass;
  
  /**
   * @var Container
   */
  private $container;
  
  /**
   * @var Scanner
   */
  private $scanner;
  
  /**
   * @var Injector
   */
  private $injector;
  
  public function __construct($moduleConfigOrClass, Injector $injector)
  {
    $this->moduleConfigOrClass = $moduleConfigOrClass;
    $this->container = new Container();
    $this->scanner = new Scanner($this->container, $injector);
    $this->injector = $injector;
  }

  public function build()
  {
    $this->scanner->scan($this->moduleConfigOrClass);

    $modules = $this->container->getModules();

    $map = [];

    foreach ($modules as $module) {
      foreach ($module->getProviders() as $provider) {
        $map[$provider->instanceClass] = $provider;
      }
    }
    
    foreach ($modules as $module) {
      foreach ($module->getControllers() as $controller) {
        $map[$controller->instanceClass] = $controller;
      }
    }

    foreach ($map as $providerClass => $provider) {

      $this->injector->instantiate($map, $provider);
      
    }

    return $this->container;
  }
}
