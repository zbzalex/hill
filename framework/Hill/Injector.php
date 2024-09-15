<?php

namespace Hill;

/**
 * Dependency injector class
 */
class Injector
{
  /**
   * @var Registry $registry Class registry
   */
  private $registry;

  /**
   * Constructor
   * 
   * @param Registry $registry Class registry
   */
  public function __construct(InstanceRegistry $registry = null)
  {
    $this->registry = $registry !== null ? $registry : new InstanceRegistry();
  }

  /**
   * Resolve instance
   * 
   * @param Module $module
   * @param InstanceWrapper $wrapper
   * 
   * @return object|null
   */
  public function instantiate(Module $module, InstanceWrapper $wrapper)
  {
    // If instance already resolved
    if ($wrapper->instance !== null) {
      return $wrapper->instance;
    }

    // If instance already resolved and registered but that wrapper is not be set.
    if (($instance = $this->registry->get($wrapper->instanceClass)) !== null) {

      // Sets reference to object
      $wrapper->instance = $instance;

      return $wrapper->instance;
    }

    // If wrapper is factory
    if ($wrapper->factory !== null) {
      // Factory must consist of two parts: invokable and invoke arguments
      // [
      //     function($a, $b) { return $a + $b; },
      //     [
      //         1,
      //         2
      //     ]
      // ]
      if (count($wrapper->factory) != 2)
        throw new \Exception(
          sprintf("Invalid arguments count in the factory")
        );
      
      // Invokes factory with arguments
      $wrapper->instance = call_user_func_array($wrapper->factory[0], $wrapper->factory[1]);

      // Register resolved instance
      $this->registry->set($wrapper->instanceClass, $wrapper->instance);

      return $wrapper->instance;
    }

    // Get providers
    $providers = $module->getProviders();

    try {
      $reflectionClass = new \ReflectionClass($wrapper->instanceClass);

      // Get injectable constructor
      $constructor = $reflectionClass->getConstructor();
      if ($constructor !== null) {

        // Get parameters
        $constructorParams = $constructor->getParameters();

        // Check if count of parameters is equal zero
        if (count($constructorParams) == 0) {
          // Instantiate a new object without parameters
          $wrapper->instance = $reflectionClass->newInstance();
        } else {

          // Argument list for class instantiation
          $deps = [];

          // Enumerate each of parameters
          foreach ($constructorParams as $param) {

            // Get param type
            /** @var \ReflectionNamedType $type */
            $type = $param->getType();

            if ($type === null)
              break;

            // Provider class
            $paramClass = $type->getName();

            // Check if provider is not defined
            if (!isset($providers[$paramClass])) {
              throw new \Exception(sprintf(
                "Unresolved instance '%s' in module '%s'",
                $paramClass,
                $module->getModuleClass()
              ));
            }

            // Get need provider by param class
            $provider = $providers[$paramClass];

            // Resolve this provider
            $instance = $this->instantiate($module, $provider);

            // Add a instance into argument list
            $deps[] = $instance;
          }

          // Instantiate a new object with arguments and set into wrapper
          $wrapper->instance = $reflectionClass->newInstanceArgs($deps);
        }
      } else {
        // Instantiate a new object without contructor
        $wrapper->instance = $reflectionClass->newInstanceWithoutConstructor();
      }

      // Register resolved instance
      $this->registry->set($wrapper->instanceClass, $wrapper->instance);

      return $wrapper->instance;
    } catch (\ReflectionException $e) {
      // ignore
    }

    return null;
  }
}
