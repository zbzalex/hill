<?php

namespace Neon;

class Injector
{
  /**
   * @var InstanceRegistry
   */
  private $registry;

  /**
   * @var array
   */
  private $dependencyGraph = [];

  /**
   * @var array
   */
  private $resolving = [];

  /**
   * @var array
   */
  private $factories = [];
  
  public function __construct(InstanceRegistry $registry = null)
  {
    $this->registry = $registry !== null ? $registry : new InstanceRegistry();
  }

  /**
   * Get instance by class name if exists
   * 
   * @param string $instanceClass
   * 
   * @return object|null
   */
  public function get($instanceClass)
  {
    $instance = $this->registry->get($instanceClass);
    if ($instance !== null) {
      return $instance;
    }
    
    if (isset($this->factories[$instanceClass])) {
      $factory = $this->factories[$instanceClass];
      return call_user_func_array($factory[0], $factory[1]);
    }

    return null;
  }

  /**
   * Build dependency graph for a class name
   * 
   * @param string  $className  Class name
   * @param array   $map        Map of dependencies
   */
  private function buildDependencyGraph($className, array $map = [])
  {
    if (isset($this->dependencyGraph[$className])) {
      return;
    }

    $this->dependencyGraph[$className] = [];

    try {
      $reflectionClass = new \ReflectionClass($className);
      $constructor = $reflectionClass->getConstructor();

      if ($constructor !== null) {

        $constructorParams = $constructor->getParameters();

        foreach ($constructorParams as $param) {
          $paramType = $param->getType();
          if ($paramType === null) break;

          $dependencyClass = $paramType->getName();

          $this->buildDependencyGraph($dependencyClass, $map);

          $this->dependencyGraph[$className][] = $dependencyClass;
        }
      } else {
        $this->dependencyGraph[$className] = [];
      }
    } catch (\ReflectionException $e) {
    }
  }

  /**
   * Resolve instance
   * 
   * @param Module $module
   * @param InstanceWrapper $wrapper
   * 
   * @return object|null
   */
  public function instantiate(array $map, InstanceWrapper $wrapper)
  {
    $className = $wrapper->instanceClass;

    if (in_array($className, $this->resolving))
      throw new \Exception('Circular dependency detected for class: ' . $className);

    $this->resolving[] = $className;

    if ($wrapper->factory !== null) {

      if (isset($this->factories[$className])) {
        $factory = $this->factories[$className];

        $this->resolving = array_diff($this->resolving, [
          $className
        ]);

        return call_user_func_array($factory[0], $factory[1]);
      }

      // Factory must consist of two parts: invokable and invoke arguments
      // [
      //     function($a, $b) { return $a + $b; },
      //     [
      //         1,
      //         2
      //     ]
      // ]
      if (count($wrapper->factory) < 2)
        throw new \Exception(
          sprintf("Factory requires 2 arguments!")
        );

      $dependencies = $wrapper->inject;
      $args = [];

      foreach ($dependencies as $dependencyClass) {
        if (!isset($map[$dependencyClass])) {
          throw new \Exception(sprintf(
            "Can't resolve dependency '%s' for a class '%s'",
            $dependencyClass,
            $className,
          ));
        }

        $dependencyWrapper = $map[$dependencyClass];
        $instance = $this->instantiate($map, $dependencyWrapper);
        $args[] = $instance;
      }

      $args[] = $wrapper->factory[1];
      $wrapper->instance = call_user_func_array($wrapper->factory[0], $args);

      $this->factories[$className] = [
        $wrapper->factory[0],
        $args,
      ];

      $this->resolving = array_diff($this->resolving, [
        $className
      ]);

      return $wrapper->instance;
    }

    if ($wrapper->instance !== null) {

      $this->resolving = array_diff($this->resolving, [
        $className
      ]);

      return $wrapper->instance;
    }

    if (($instance = $this->registry->get($wrapper->instanceClass)) !== null) {

      $wrapper->instance = $instance;

      $this->resolving = array_diff($this->resolving, [
        $className
      ]);

      return $wrapper->instance;
    }

    if ($wrapper->provider === null) {
      $this->buildDependencyGraph($className, $map);
    }

    $dependencies = $wrapper->provider === null
      ? $this->dependencyGraph[$className]
      : $wrapper->inject;
    $args = [];

    foreach ($dependencies as $dependencyClass) {
      if (!isset($map[$dependencyClass])) {
        throw new \Exception(sprintf(
          "Can't resolve dependency '%s' for a class '%s'",
          $dependencyClass,
          $className,
        ));
      }

      $dependencyWrapper = $map[$dependencyClass];
      $instance = $this->instantiate($map, $dependencyWrapper);
      $args[] = $instance;
    }

    if ($wrapper->provider === null) {
      $instance = Reflector::instantiate($className, $args);
    } else {
      
      $args[] = $wrapper->provider[1];

      $instance = call_user_func_array($wrapper->provider[0], $args);
    }

    $wrapper->instance = $instance;
    $this->registry->set($className, $wrapper->instance);

    $this->resolving = array_diff($this->resolving, [
      $className
    ]);

    return $instance;
  }
}
