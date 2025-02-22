<?php

namespace Neon;

class Scanner
{
  /**
   * @var Container
   */
  private $container;

  /**
   * @var Injector
   */
  private $injector;

  /**
   * Constructor
   * 
   * @param Container $container  Module container
   * @param Injector $injector    Class injector
   */
  public function __construct(Container $container, Injector $injector)
  {
    $this->container = $container;
    $this->injector = $injector;
  }

  public function scan($moduleConfigOrClass)
  {
    $this->scanModule($moduleConfigOrClass);
  }

  public function resolveModuleConfig($moduleConfigOrClass)
  {
    if (is_array($moduleConfigOrClass)) {
      return $moduleConfigOrClass;
    } else if (is_string($moduleConfigOrClass)) {
      return self::createModuleForClass($moduleConfigOrClass);
    }

    return null;
  }

  private static function createModuleForClass($moduleClass)
  {
    $reflectionClass = new \ReflectionClass($moduleClass);
    if (!$reflectionClass->implementsInterface(IModule::class)) {
      throw new \Exception(
        sprintf("Class '%s' is not implements IModule", $moduleClass)
      );
    }

    $moduleConfig = $reflectionClass->getMethod('create')->invoke(null);

    return $moduleConfig;
  }

  private function scanModule($moduleConfigOrClass)
  {
    // Resolve module config
    $moduleConfig = $this->resolveModuleConfig($moduleConfigOrClass);
    $moduleClass = $moduleConfig !== null && isset($moduleConfig['moduleClass'])
      ? $moduleConfig['moduleClass']
      : null;

    if ($moduleConfig === null)
      throw new \Exception(
        "Module config is not defined"
      );
    if ($moduleClass === null)
      throw new \Exception(sprintf(
        "Undefined 'moduleClass' property"
      ));

    // if module exists
    if (($module = $this->container->getModule($moduleClass)) !== null) {
      return $module;
    }

    $module = new Module($moduleClass, $moduleConfig, $this->injector);

    $this->container->addModule($module);

    $providers = isset($moduleConfig['providers'])
      && is_array($moduleConfig['providers'])
      ? $moduleConfig['providers']
      : [];

    $controllers = isset($moduleConfig['controllers'])
      && is_array($moduleConfig['controllers'])
      ? $moduleConfig['controllers']
      : [];

    $importModules = isset($moduleConfig['importModules'])
      && is_array($moduleConfig['importModules'])
      ? $moduleConfig['importModules']
      : [];

    $this->addProviders($module, $providers);
    $this->addControllers($module, $controllers);
    $this->addImportModules($module, $importModules);

    return $module;
  }

  private function addProviders(Module $module, array $providers)
  {
    foreach ($providers as $providerConfigOrClass) {
      $provider = null;
      $inject = [];
      $factory = null;

      if (is_array($providerConfigOrClass)) {
        $providerClass = isset($providerConfigOrClass['providerClass'])
          ? $providerConfigOrClass['providerClass']
          : null;

        if ($providerClass === null)
          continue;

        if (
          !isset($providerConfigOrClass['provider'])
          && !isset($providerConfigOrClass['factory'])
        )
          continue;

        if (isset($providerConfigOrClass['provider'])) {
          if (!is_array($providerConfigOrClass['provider'])
          || count($providerConfigOrClass['provider']) < 2)
            continue;
          
          $provider = $providerConfigOrClass['provider'];
        } else if (isset($providerConfigOrClass['factory'])) {
          if (
            !is_array($providerConfigOrClass['factory'])
            || count($providerConfigOrClass['factory']) < 2
          )
            continue;

          $factory = $providerConfigOrClass['factory'];
        }

        if (isset($providerConfigOrClass['inject'])) {
          $inject = $providerConfigOrClass['inject'];
        }
      } else {
        if ($providerConfigOrClass === null)
          continue;

        $providerClass = $providerConfigOrClass;
      }

      $wrapper = $module->addProvider($providerClass, $factory);
      $wrapper->provider    = $provider;
      $wrapper->inject      = $inject;
    }
  }

  private function addControllers(Module $module, array $controllers)
  {
    foreach ($controllers as $controllerClass) {
      if ($controllerClass === null)
        continue;

      if (!Reflector::implementsInterface($controllerClass, IController::class))
        continue;

      $module->addController($controllerClass);
    }
  }

  private function addImportModules(Module $module, array $importModules)
  {
    foreach ($importModules as $importModuleConfigOrClass) {
      $importModuleConfig = [];
      $importModule = null;
      if (is_array($importModuleConfigOrClass)) {
        $importModuleConfig = $importModuleConfigOrClass;
      } else if (is_string($importModuleConfigOrClass)) {

        if ($importModuleConfigOrClass === null)
          continue;

        $importModule = $this->container->getModule($importModuleConfigOrClass);
        if ($importModule === null) {
          $importModuleConfig = self::createModuleForClass($importModuleConfigOrClass);
        }
      } else {
        continue;
      }

      if ($importModule === null) {
        $importModule = $this->scanModule($importModuleConfig);
      }

      $module->addImport($importModule);
    }
  }
}
