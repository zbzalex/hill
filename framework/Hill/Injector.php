<?php

namespace Hill;

/**
 * Dependency injector class
 */
class Injector
{
    /** @var Registry $registry */
    private $registry;

    /**
     * Constructor
     * 
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param Module $module
     * @param InstanceWrapper $wrapper
     * 
     * @return object|null
     */
    public function resolveInstance(
        Module $module,
        InstanceWrapper $wrapper
    ) {
        if ($wrapper->instance !== null) {
            return $wrapper->instance;
        }

        if (($instance = $this->registry->get($wrapper->instanceClass)) !== null) {
            $wrapper->instance = $instance;

            return $wrapper->instance;
        }

        if ($wrapper->factory !== null) {
            if (count($wrapper->factory) != 2)
                throw new \Exception(
                    sprintf("Invalid argument count in factory function")
                );

            $wrapper->instance = call_user_func_array($wrapper->factory[0], $wrapper->factory[1]);
            return $wrapper->instance;
        }

        $providers = $module->getProviders();

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
                                "Unresolved instance '%s' in module '%s'",
                                $paramClass,
                                $module->getModuleClass()
                            ));
                        }

                        $provider = $providers[$paramClass];
                        $instance = $this->resolveInstance($module, $provider);

                        $args[] = $instance;
                    }

                    $wrapper->instance = $reflectionClass->newInstanceArgs($args);
                }
            } else {
                $wrapper->instance = $reflectionClass->newInstanceWithoutConstructor();
            }

            $this->registry->set($wrapper->instanceClass, $wrapper->instance);

            return $wrapper->instance;
        } catch (\ReflectionException $e) {
        }

        return null;
    }
}
