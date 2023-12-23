<?php

namespace Hill;

/**
 * Dependency scanner class
 */
class DependencyScanner
{
    /**
     * @var Container $container
     */
    private $container;

    /**
     * Constructor
     * 
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Scan modules
     * 
     * @param array $rootModuleConfig
     */
    public function scan($rootModuleConfigOrClass)
    {
        $this->scanForModules($rootModuleConfigOrClass);
        $this->resolveModules();
    }

    private function resolveModules()
    {
        $modules  = array_merge(
            $this->container->getGlobalModules(),
            $this->container->getModules()
        );
        foreach ($modules as $module) {
            $this->resolveModuleImports($module);
        }
    }

    private function resolveModuleImports(Module $module)
    {
        $importModules = array_merge($this->container->getGlobalModules(), $module->getImports());
        
        foreach ($importModules as $importModule) {
            $importModuleConfig = $importModule->getConfig();
            $exportProviders = isset($importModuleConfig['exportProviders'])
                && is_array($importModuleConfig['exportProviders'])
                ? $importModuleConfig['exportProviders']
                : [];

            foreach ($exportProviders as $exportProviderClass) {
                $this->resolveProviderForModule($module, $importModule, $exportProviderClass);
            }
        }
    }

    /**
     * Creates module config from module class
     * 
     * @param string $moduleClass
     * 
     * @throws \ReflectionException
     * 
     * @return array
     */
    private static function createModuleForClass($moduleClass)
    {
        $reflectionClass = new \ReflectionClass($moduleClass);
        if (!$reflectionClass->implementsInterface(\Hill\IModule::class)) {
            throw new \ReflectionException(
                sprintf("Module '%s' must be implements of \Hill\IModule", $moduleClass)
            );
        }

        $createMethod = $reflectionClass->getMethod('create');
        if (!$createMethod->isStatic() || !$createMethod->isPublic()) {
            throw new \ReflectionException(
                "Module method 'create' must be public static!"
            );
        }

        $moduleConfig = $createMethod->invoke(null);

        return $moduleConfig;
    }

    /**
     * Scan modules
     * 
     * @param string|array $moduleConfigOrClass
     * 
     * @throws \Exception
     * @throws \ReflectionException
     * 
     * @return Module
     */
    private function scanForModules($moduleConfigOrClass)
    {
        $moduleConfig = [];

        // resolve module
        if (is_array($moduleConfigOrClass)) {
            $moduleConfig = $moduleConfigOrClass;
            $moduleClass = isset($moduleConfig['moduleClass'])
                ? $moduleConfig['moduleClass']
                : null;

            if ($moduleClass === null)
                throw new \Exception(
                    "Module class property isnt specified!"
                );
        } else {
            $moduleClass = $moduleConfigOrClass;
            if (($moduleConfig = self::createModuleForClass($moduleClass)) === null)
                throw new \Exception(
                    sprintf("Failed to create module '%s'", $moduleClass)
                );
        }

        // check if module already emplaced and return it if found
        if (($module = $this->container->getModule($moduleClass)) !== null) {
            return $module;
        }

        // detect module scope and emplace into module container
        $module = isset($moduleConfig['global']) && $moduleConfig['global'] === true
            ? $this->container->emplaceAndGetGlobalModule($moduleClass, $moduleConfig)
            : $this->container->emplaceAndGetModule($moduleClass, $moduleConfig);

        // scan module for dependencies
        try {
            $providers = isset($moduleConfig['providers'])
                && is_array($moduleConfig['providers'])
                ? $moduleConfig['providers']
                : [];

            $controllers = isset($moduleConfig['controllers'])
                && is_array($moduleConfig['controllers'])
                ? $moduleConfig['controllers']
                : [];

            foreach ($providers as $providerConfigOrClass) {
                try {
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
                        $reflectionClass = new \ReflectionClass($providerClass);
                        if (!$reflectionClass->implementsInterface(IInjectable::class))
                            continue;
                    }

                    $module->addProvider($providerClass, $factory);
                } catch (\ReflectionException $e) {
                }
            }

            foreach ($controllers as $controllerClass) {
                if ($controllerClass === null)
                    continue;

                try {
                    $reflectionClass = new \ReflectionClass($controllerClass);
                    if (!$reflectionClass->implementsInterface(IController::class))
                        continue;

                    $module->addController($controllerClass);
                } catch (\ReflectionException $e) {
                }
            }

            // must be optional property!
            $importModules = isset($moduleConfig['importModules'])
                && is_array($moduleConfig['importModules'])
                ? $moduleConfig['importModules']
                : [];

            $this->scanModuleForImports($module, $importModules);
        } catch (\ReflectionException $e) {
        }

        return $module;
    }

    private function scanModuleForImports(Module $module, array $importModules)
    {
        foreach ($importModules as $importModuleConfigOrClass) {
            $importModuleConfig = [];

            if (is_array($importModuleConfigOrClass)) {
                $importModuleConfig = $importModuleConfigOrClass;
            } else {
                if ($importModuleConfigOrClass === null)
                    continue;

                if (($importModule_ = $this->container->getModule($importModuleConfigOrClass)) !== null) {
                    $importModuleConfig = $importModule_->getConfig();
                } else {
                    $importModuleConfig = self::createModuleForClass($importModuleConfigOrClass);
                }
            }

            $importModule = $this->scanForModules($importModuleConfig);
            if (!isset($importModuleConfig['global']) || !$importModuleConfig['global']) {
                $module->addImport($importModule);
            }
        }
    }

    /**
     * Resolve provider dependencies for module
     * 
     * @param Module $module            The module
     * @param Module $importModule      Related module
     * @param string $providerClass     Provider class
     */
    private function resolveProviderForModule(Module $module, Module $importModule, $providerClass)
    {
        $providers = $importModule->getProviders();
        if (isset($providers[$providerClass])) {

            $provider = $providers[$providerClass];

            // check if provider isnt factory and resolve deps for export
            if ($provider->factory === null) {
                try {
                    $deps = Reflector::getConstructorArgs($providerClass);
                    foreach ($deps as $depProviderClass) {
                        $this->resolveProviderForModule($module, $importModule, $depProviderClass);
                    }
                } catch (\ReflectionException $e) {
                }
            }

            $module->addProvider($providerClass, $provider->factory);
        }
    }
}
