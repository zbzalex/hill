<?php

namespace Hill;

/**
 * Container builder class
 */
class ContainerBuilder
{
  /** @var string|array $moduleConfigOrClass */
  private $moduleConfigOrClass;
  /** @var Container $container */
  private $container;
  /** @var Scanner $scanner */
  private $scanner;
  /** @var Injector $injector */
  private $injector;
  
  /**
   * Constructor
   * 
   * @param array|string $moduleConfigOrClass Module config or module class
   */
  public function __construct($moduleConfigOrClass, Injector $injector)
  {
    $this->moduleConfigOrClass = $moduleConfigOrClass;
    $this->container = new Container();
    $this->scanner = new Scanner($this->container, $injector);
    $this->injector = $injector;
  }

  /**
   * Build container modules and they dependencies
   * 
   * @return Container
   */
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
