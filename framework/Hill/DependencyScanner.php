<?php

namespace Hill;

/**
 * Dependency scanner class
 */
class DependencyScanner
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
        $this->buildModules();
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

        // Check is module registered
        if (($module = $this->container->getModule($moduleClass)) !== null) {
            return $module;
        }

        // Detect module scope
        $isGlobal = isset($moduleConfig['global'])
            && $moduleConfig['global'] === true;

        // Create a new module
        $module = new Module($moduleClass, $moduleConfig);
        // and register him
        $this->container->registerModule(
            $module,
            $isGlobal
                ? Container::MODULE_SCOPE_GLOBAL
                : Container::MODULE_SCOPE_NULL
        );

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

        $this->scanModuleForProviders($module, $providers);
        $this->scanModuleForControllers($module, $controllers);
        $this->scanModuleForImports($module, $importModules);

        return $module;
    }

    /**
     * Scan module for providers
     * 
     * @param Module $module    Module
     * @param array $providers  Module providers
     */
    private function scanModuleForProviders(Module $module, array $providers)
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
    private function scanModuleForControllers(Module $module, array $controllers)
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
    private function scanModuleForImports(Module $module, array $importModules)
    {
        foreach ($importModules as $importModuleConfigOrClass) {
            $importModuleConfig = [];
            $importModule = null;
            if (is_array($importModuleConfigOrClass)) {
                $importModuleConfig = $importModuleConfigOrClass;
            } else if (is_string($importModuleConfigOrClass)) {
                // Fixed: bug with null module class
                if ($importModuleConfigOrClass === null)
                    continue;

                $importModule = $this->container->getModule($importModuleConfigOrClass);
                if ($importModule === null) {
                    $importModuleConfig = self::createModuleForClass($importModuleConfigOrClass);
                }
            } else { // else invalid type
                continue;
            }

            // Scan module is not registered
            if ($importModule === null) {
                $importModule = $this->scanModule($importModuleConfig);
            }

            // Ignore global module
            if ($importModule->isGlobal())
                continue;

            $module->addImport($importModule);
        }
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
     * Build modules
     */
    private function buildModules()
    {
        // Select all modules with globals for resolve exports
        $modules  = array_merge($this->container->getModules(), $this->container->getGlobalModules());
        
        foreach ($modules as $module) {
            // Current module imports with global modules
            $imports = array_merge($this->container->getGlobalModules(), $module->getImports());

            foreach ($imports as $importModule) {
                $this->exportProviders($importModule, $module);
            }
        }
    }

    /**
     * Export module export providers
     * 
     * @param Module $exporter Exporter
     * @param Module $importer Importer
     */
    private function exportProviders(Module $exporter, Module $importer)
    {
        $importModuleConfig = $exporter->getConfig();

        $exportProviders = isset($importModuleConfig['exportProviders'])
            && is_array($importModuleConfig['exportProviders'])
            ? $importModuleConfig['exportProviders']
            : [];

        // Resolve import module export services
        foreach ($exportProviders as $exportProviderClass) {
            $this->exportProviderWithDeps($exporter, $importer, $exportProviderClass);
        }
    }

    /**
     * Export provider with dependencies from export module to import module
     * 
     * @param Module $exporter          Exporter
     * @param Module $importer          Importer
     * @param string $providerClass     Provider class
     */
    private function exportProviderWithDeps(
        Module $exporter,
        Module $importer,
        string $providerClass
    ) {
        $providers = $exporter->getProviders();
        if (isset($providers[$providerClass]))
            throw new \Exception(sprintf(
                "Provider '%s' not specified in module '%s'",
                $providerClass,
                $exporter->getModuleClass(),
            ));

        $provider = $providers[$providerClass];

        if ($provider->factory === null) {

            // Scan provider for dependencies
            try {
                $deps = Reflector::getConstructorArgs($providerClass);
                foreach ($deps as $depProviderClass) {
                    $this->exportProviderWithDeps($importer, $exporter, $depProviderClass);
                }
            } catch (\ReflectionException $e) {
            }
        }

        $importer->addProvider($providerClass, $provider->factory);
    }
}
