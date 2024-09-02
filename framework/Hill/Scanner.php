<?php

namespace Hill;

/**
 * Scanner class
 */
class Scanner
{
  /**
   * @var Container $container Container
   */
  private $container;

  /**
   * Constructor
   * 
   * @param Container $container Container
   */
  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  /**
   * Scan modules from root
   * 
   * @param array|string $moduleConfigOrClass Module config or module class
   */
  public function scan($moduleConfigOrClass)
  {
    $this->scanModule($moduleConfigOrClass);
    $this->inject();
  }

  /**
   * Resolve module config
   * 
   * @param array|string $moduleConfigOrClass Module config or module class
   * 
   * @return array|null
   */
  public function resolveModuleConfig($moduleConfigOrClass)
  {
    if (is_array($moduleConfigOrClass)) {
      return $moduleConfigOrClass;
    } else if (is_string($moduleConfigOrClass)) {
      return self::createModuleForClass($moduleConfigOrClass);
    }

    return null;
  }

  /**
   * Creates module config from module class
   * 
   * @param string $moduleClass
   * 
   * @return array
   */
  private static function createModuleForClass($moduleClass)
  {
    $reflectionClass = new \ReflectionClass($moduleClass);
    if (!$reflectionClass->implementsInterface(\Hill\IModule::class)) {
      throw new \Exception(
        sprintf("Class '%s' is not implements \Hill\IModule", $moduleClass)
      );
    }

    $moduleConfig = $reflectionClass->getMethod('create')->invoke(null);

    return $moduleConfig;
  }

  /**
   * Scan module
   * 
   * @param array|string $moduleConfigOrClass Module config or module class
   * 
   * @return Module
   */
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

    $module = new Module($moduleClass, $moduleConfig);

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

    $this->resolveProviders($module, $providers);
    $this->resolveControllers($module, $controllers);
    $this->resolveImports($module, $importModules);

    return $module;
  }

  /**
   * Scan module for providers
   * 
   * @param Module $module    Module
   * @param array $providers  Module providers
   */
  private function resolveProviders(Module $module, array $providers)
  {
    foreach ($providers as $providerConfigOrClass) {

      /** @var array|null $factory Provider factory */
      $factory = null;

      if (is_array($providerConfigOrClass)) {
        $providerClass = isset($providerConfigOrClass['providerClass'])
          ? $providerConfigOrClass['providerClass']
          : null;
        if ($providerClass === null)
          continue;

        if (!isset($providerConfigOrClass['factory']))
          continue;

        $factory = $providerConfigOrClass['factory'];
      } else {
        if ($providerConfigOrClass === null)
          continue;

        $providerClass = $providerConfigOrClass;

        // Provider class must implements IInjectable interface
        if (!Reflector::implementsInterface($providerClass, IInjectable::class))
          continue;
      }

      $module->addProvider($providerClass, $factory);
    }
  }

  /**
   * Scan module for controllers
   * 
   * @param Module $module        Module
   * @param array $controllers    Module controllers
   */
  private function resolveControllers(Module $module, array $controllers)
  {
    foreach ($controllers as $controllerClass) {
      if ($controllerClass === null)
        continue;

      // Controller must implements IController interface
      if (!Reflector::implementsInterface($controllerClass, IController::class))
        continue;

      $module->addController($controllerClass);
    }
  }

  /**
   * Scan module for import modules
   * 
   * @param Module    $module         Module
   * @param Module[]  $importModules  Module imports
   */
  private function resolveImports(Module $module, array $importModules)
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

  private function inject()
  {
    $modules  = $this->container->getModules();
    foreach ($modules as $module) {
      $imports = $module->getImports();
      foreach ($imports as $exporter) {
        $this->export($exporter, $module);
      }
    }
  }

  /**
   * Export module export providers
   * 
   * @param Module $exporter Exporter
   * @param Module $importer Importer
   */
  private function export(Module $exporter, Module $importer)
  {
    $importModuleConfig = $exporter->getConfig();

    $exports = isset($importModuleConfig['exports'])
      && is_array($importModuleConfig['exports'])
      ? $importModuleConfig['exports']
      : [];

    // Resolve import module export services
    foreach ($exports as $injectableClass) {
      $this->resolveInjectable($exporter, $importer, $injectableClass);
    }
  }

  /**
   * Export provider with dependencies from export module to import module
   * 
   * @param Module $exporter          Exporter
   * @param Module $importer          Importer
   * @param string $injectableClass     Injectable class
   */
  private function resolveInjectable(
    Module $exporter,
    Module $importer,
    string $injectableClass
  ) {
    $providers = $exporter->getProviders();
    if (!isset($providers[$injectableClass]))
      throw new \Exception(sprintf(
        "Provider '%s' not found in module '%s'",
        $injectableClass,
        $exporter->getModuleClass(),
      ));

    $provider = $providers[$injectableClass];

    if ($provider->factory === null) {

      // Scan provider for dependencies
      try {
        $deps = Reflector::getConstructorArgs($injectableClass);
        foreach ($deps as $depProviderClass) {
          $this->resolveInjectable($importer, $exporter, $depProviderClass);
        }
      } catch (\ReflectionException $e) {
      }
    }

    $importer->addProvider($injectableClass, $provider->factory);
  }
}
