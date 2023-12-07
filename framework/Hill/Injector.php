<?php

namespace Hill;

/**
 * Dependency injector class
 */
class Injector
{
    /**
     * @var Module $module
     */
    private $module;

    /**
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * @param InstanceWrapper $wrapper
     * 
     * @return object|null
     */
    public function resolveInstance(
        InstanceWrapper $wrapper
    ) {
        if ($wrapper->instance !== null)
            return $wrapper->instance;

        if ($wrapper->factory !== null) {
            if (count($wrapper->factory) != 2)
                throw new \Exception(
                    sprintf("Invalid argument count in factory function")
                );

            $wrapper->instance = call_user_func_array($wrapper->factory[0], $wrapper->factory[1]);
            return $wrapper->instance;
        }

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
                        /** @var \ReflectionNamedType $type */
                        $type = $param->getType();

                        if ($type === null)
                            break;

                        $paramClass = $type->getName();

                        if (!isset($providers[$paramClass])) {
                            throw new \Exception(sprintf(
                                "Unresolved instance '%s' in module '%s'", $paramClass, $this->module->getModuleClass()
                            ));
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

    /**
     * @param string $someClass
     * 
     * @return bool
     */
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
