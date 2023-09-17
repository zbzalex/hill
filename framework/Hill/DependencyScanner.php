<?php

namespace Hill;

//
//
//
class DependencyScanner
{
    /**
     * @var Container $container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $rootModuleConfig
     */
    public function scan($rootModuleConfigOrClass)
    {
        $this->scanForModules($rootModuleConfigOrClass);
    }

    /**
     * @param strint $moduleClass
     * 
     * @throws \ReflectionException
     * 
     * @return array
     */
    private function createModule($moduleClass)
    {
        $reflectionClass = new \ReflectionClass($moduleClass);
        if (!$reflectionClass->implementsInterface(\Hill\IModule::class)) {
            throw new \ReflectionException(
                sprintf("Module '%s' must be implements of \Hill\IModule", $moduleClass)
            );
        }

        $moduleConfig = $reflectionClass->getMethod('create')->invoke(null);

        return $moduleConfig;
    }

    /**
     * @param string|array $moduleConfigOrClass
     * 
     * @throws \Exception
     * @throws \ReflectionException
     * 
     * @return Module|null
     */
    private function scanForModules($moduleConfigOrClass)
    {
        $moduleConfig = [];

        if (is_array($moduleConfigOrClass)) {
            $moduleConfig = $moduleConfigOrClass;
            $moduleClass = isset($moduleConfig['moduleClass'])
                ? $moduleConfig['moduleClass']
                : null;

            if ($moduleClass === null)
                throw new \Exception(
                    "Where is module class in your module config?"
                );
        } else {
            $moduleClass = $moduleConfigOrClass;
            if (($moduleConfig = self::createModule($moduleClass)) === null)
                throw new \Exception(
                    sprintf("Failed to create module '%s'", $moduleClass)
                );
        }

        if (($module = $this->container->get($moduleClass)) !== null) {
            return $module;
        }

        // push module into container
        $module = $this->container->addModule($moduleClass, $moduleConfig);

        $this->scanModuleForDeps($module);

        $importModules = isset($moduleConfig['importModules'])
            && is_array($moduleConfig['importModules'])
            ? $moduleConfig['importModules']
            : [];

        foreach ($importModules as $importModuleConfigOrClass) {
            $importModuleConfig = [];
            if (is_array($importModuleConfigOrClass)) {
                $importModuleConfig = $importModuleConfigOrClass;
            } else {
                if (($importModule_ = $this->container->get($importModuleConfigOrClass)) !== null) {
                    $importModuleConfig = $importModule_->getConfig();
                } else {
                    $importModuleConfig = self::createModule($importModuleConfigOrClass);
                }
            }

            $importModule = $this->scanForModules($importModuleConfig);

            // resolve exported providers from imported modules
            $exportProviders = isset($importModuleConfig['exportProviders'])
                && is_array($importModuleConfig['exportProviders'])
                ? $importModuleConfig['exportProviders']
                : [];

            foreach ($exportProviders as $exportProviderClass) {
                $this->resolveProviderDependencies($module, $importModule, $exportProviderClass);
            }
        }

        return $module;
    }

    /**
     * @param Module $module
     */
    private function scanModuleForDeps(Module $module)
    {
        $config = $module->getConfig();

        try {
            $providers = isset($config['providers'])
                && is_array($config['providers'])
                ? $config['providers']
                : [];

            $controllers = isset($config['controllers'])
                && is_array($config['controllers'])
                ? $config['controllers']
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
                try {
                    $reflectionClass = new \ReflectionClass($controllerClass);
                    if (!$reflectionClass->implementsInterface(IController::class))
                        continue;

                    $module->addController($controllerClass);
                } catch (\ReflectionException $e) {
                }
            }
        } catch (\ReflectionException $e) {
        }
    }

    /**
     * @param Module $module
     * @param Module $importModule
     * @param string $providerClass
     */
    private function resolveProviderDependencies(Module $module, Module $importModule, $providerClass)
    {
        $providers = $importModule->getProviders();
        if (isset($providers[$providerClass])) {

            $provider = $providers[$providerClass];
            if ($provider->factory === null) {
                try {
                    $dependencies = Reflector::getConstructorArgs($providerClass);
                    foreach ($dependencies as $dependencyProviderClass) {
                        $this->resolveProviderDependencies($module, $importModule, $dependencyProviderClass);
                    }
                } catch (\ReflectionException $e) {
                }
            }

            $module->addProvider($providerClass, $provider->factory);
        }
    }
}
