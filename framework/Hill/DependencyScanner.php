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
     * @param array $rootModuleConfigOrClass
     */
    public function scan($rootModuleConfigOrClass)
    {
        $this->scanModule($rootModuleConfigOrClass);
        $this->resolveModules();
    }

    /**
     * 
     */
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

    /**
     * 
     */
    private function resolveModuleImports(Module $module)
    {
        $importModules = array_merge(
            $this->container->getGlobalModules(),
            $module->getImports()
        );

        foreach ($importModules as $importModule) {
            $importModuleConfig = $importModule->getConfig();
            $exportProviders = isset($importModuleConfig['exportProviders'])
                && is_array($importModuleConfig['exportProviders'])
                ? $importModuleConfig['exportProviders']
                : [];

            foreach ($exportProviders as $exportProviderClass) {
                $this->resolveProviderDeps($module, $importModule, $exportProviderClass);
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
    private function scanModule(
        $moduleConfigOrClass
    ) {
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

        $isGlobal = isset($moduleConfig['global'])
            && $moduleConfig['global'] === true;

        // detect module scope and emplace into module container
        if ($isGlobal) {
            $this->container->addGlobalModule(
                $moduleClass,
                $moduleConfig
            );
        } else {
            $this->container->addModule(
                $moduleClass,
                $moduleConfig
            );
        }

        $module = $this->container->getModule($moduleClass);

        try {
            $providers = isset($moduleConfig['providers'])
                && is_array($moduleConfig['providers'])
                ? $moduleConfig['providers']
                : [];

            $controllers = isset($moduleConfig['controllers'])
                && is_array($moduleConfig['controllers'])
                ? $moduleConfig['controllers']
                : [];

            //// providers
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

            //// controllers
            foreach ($controllers as $controllerClass) {
                if ($controllerClass === null)
                    continue;

                try {
                    if (!Reflector::implementsInterface($controllerClass, IController::class)) continue;

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

    /**
     * Scan source module for import modules
     * 
     * @param Module    $module         Source module
     * @param Module[]  $importModules  Source module imports
     */
    private function scanModuleForImports(Module $module, array $importModules = [])
    {
        foreach ($importModules as $importModuleConfigOrClass) {
            $importModuleConfig = [];

            $mod = null;

            if (is_array($importModuleConfigOrClass)) {
                $importModuleConfig = $importModuleConfigOrClass;
            } else {

                // fix bug with null module class
                if ($importModuleConfigOrClass === null)
                    continue;

                $mod = $this->container->getModule($importModuleConfigOrClass);
                if ($mod === null) {
                    $importModuleConfig = self::createModuleForClass($importModuleConfigOrClass);
                }
            }

            if ($mod === null) {
                $mod = $this->scanModule($importModuleConfig);
            }

            if (!$mod->isGlobal()) {
                $module->addImport($mod);
            }
        }
    }

    /**
     * Resolve provider dependencies for module
     * 
     * @param Module $module            src module
     * @param Module $importModule      dst module
     * @param string $providerClass     provider class
     */
    private function resolveProviderDeps(
        Module $src,
        Module $dst,
        string $providerClass
    ) {
        $providers = $dst->getProviders();
        if (isset($providers[$providerClass])) {

            $provider = $providers[$providerClass];

            // check if provider isnt factory and resolve deps for export
            if ($provider->factory === null) {
                try {
                    $deps = Reflector::getConstructorArgs($providerClass);
                    foreach ($deps as $depProviderClass) {
                        $this->resolveProviderDeps($src, $dst, $depProviderClass);
                    }
                } catch (\ReflectionException $e) {
                }
            }

            $src->addProvider($providerClass, $provider->factory);
        }
    }
}
