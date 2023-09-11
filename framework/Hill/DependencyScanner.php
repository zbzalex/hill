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
     * 
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $moduleClass
     */
    public function scan($moduleClass)
    {
        $this->scanForModules($moduleClass);
        $this->scanModulesForDeps();
    }

    /**
     * 
     */
    private function scanForModules($moduleClass)
    {
        if ($this->container->get($moduleClass) !== null) {
            return null;
        }

        if (($module = $this->container->addModule($moduleClass)) === null)
            return null;

        try {
            $reflectionClass = new \ReflectionClass($moduleClass);

            $importModules = $reflectionClass
                ->getMethod('importModules')
                ->invoke(null);
            
            foreach ($importModules as $importModuleClass) {
                $this->scanForModules($importModuleClass);

                // resolve exported providers from imported modules
                try {
                    $reflectionClass = new \ReflectionClass($importModuleClass);
                    $exportProviders = $reflectionClass
                        ->getMethod('exportProviders')
                        ->invoke(null);
                    
                    foreach ($exportProviders as $exportProviderClass) {
                        $this->resolveProviderDependencies($module, $exportProviderClass);
                    }
                } catch (\ReflectionException $e) {
                }
            }
        } catch (\ReflectionException $e) {
        }
    }

    /**
     * 
     */
    private function scanModulesForDeps()
    {
        $modules = $this->container->getModules();

        foreach ($modules as $module) {
            $moduleClass = $module->getModuleClass();

            try {
                $reflectionClass = new \ReflectionClass($moduleClass);

                $providers = $reflectionClass
                    ->getMethod('providers')
                    ->invoke(null);
                $controllers = $reflectionClass
                    ->getMethod('controllers')
                    ->invoke(null);

                foreach ($providers as $providerClass) {
                    try {
                        $reflectionClass = new \ReflectionClass($providerClass);
                        if (!$reflectionClass->implementsInterface(IInjectable::class))
                            continue;

                        $module->addProvider($providerClass);
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
    }

    private static function getConstructorArgs($someClass)
    {
        try {
            $reflectionClass = new \ReflectionClass($someClass);

            $constructor = $reflectionClass->getConstructor();
            if ($constructor !== null) {
                $constructorParams = $constructor->getParameters();
                if (count($constructorParams) != 0) {
                    $args = [];

                    foreach ($constructorParams as $param) {
                        $paramClass = $param->getClass()->getName();

                        $args[] = $paramClass;
                    }

                    return $args;
                }
            }
        } catch (\ReflectionException $e) {
        }

        return [];
    }

    private function resolveProviderDependencies(Module $module, $providerClass)
    {
        try {
            $reflectionClass = new \ReflectionClass($providerClass);
            if ($reflectionClass->implementsInterface(IInjectable::class)) {
                $dependencies = self::getConstructorArgs($providerClass);

                // resolve dependencies
                foreach ($dependencies as $dependencyProviderClass) {
                    $this->resolveProviderDependencies($module, $dependencyProviderClass);
                }

                $module->addProvider($providerClass);
            }
        } catch (\ReflectionException $e) {
        }
    }
}
