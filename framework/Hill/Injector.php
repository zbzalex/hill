<?php

namespace Hill;

//
//
//
class Injector
{
    /**
     * 
     */
    private $module;

    /**
     * 
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * 
     */
    public function resolveInstance(
        InstanceWrapper $wrapper
    ) {
        if ($wrapper->instance !== null)
            return $wrapper->instance;

        $providers = $this->module->getProviders();

        try {
            $reflectionClass = new \ReflectionClass($wrapper->instanceClass);

            $constructor = $reflectionClass->getConstructor();
            if ($constructor !== null) {
                $constructorParams = $constructor->getParameters();
                if (count($constructorParams) == 0) {
                    $wrapper->instance = $reflectionClass->newInstance();
                } else {

                    $args = [];
                    
                    foreach ($constructorParams as $param) {
                        $paramClass = $param->getClass()->getName();

                        if (!isset($providers[$paramClass])) {
                            throw new \Exception(sprintf("Unresolved instance '%s' in module '%s'", $paramClass, $this->module->getModuleClass()));
                        }

                        $provider = $providers[$paramClass];
                        $instance = $this->resolveInstance($provider);

                        $args[] = $instance;
                    }

                    $wrapper->instance = $reflectionClass->newInstanceArgs($args);
                }
            } else {
                $wrapper->instance = $reflectionClass->newInstanceWithoutConstructor();
            }

            return $wrapper->instance;
        } catch (\ReflectionException $e) {
        }

        return null;
    }

    public static function isInjectable($someClass)
    {
        try {
            $reflectionClass = new \ReflectionClass($someClass);

            return $reflectionClass->implementsInterface(IInjectable::class);
        } catch (\ReflectionException $e) {
        }
        return false;
    }
}
