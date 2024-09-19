<?php

namespace Neon;

class Reflector
{
  public static function getConstructorArgs($someClass)
  {
    try {
      $reflectionClass = new \ReflectionClass($someClass);

      $constructor = $reflectionClass->getConstructor();
      if ($constructor !== null) {
        $constructorParams = $constructor->getParameters();
        if (count($constructorParams) != 0) {
          $args = [];
          foreach ($constructorParams as $param) {

            /** @var \ReflectionNamedType $type */
            $type = $param->getType();
            if ($type === null)
              break;

            $args[] = $type->getName();
          }

          return $args;
        }
      }
    } catch (\ReflectionException $e) {
    }

    return [];
  }

  public static function implementsInterface($class, $interface)
  {
    try {
      $reflector = new \ReflectionClass($class);

      return $reflector->implementsInterface($interface);
    } catch (\ReflectionException $e) {
    }

    return false;
  }

  public static function invokeArgs($class, $methodName, $thisObject = null, array $args = [])
  {
    try {
      $reflector = new \ReflectionClass($class);

      $method = $reflector->getMethod($methodName);

      return $method->invokeArgs($thisObject, $args);
    } catch (\ReflectionException $e) {
    }

    return null;
  }

  public static function instantiate($class, array $args = [])
  {
    try {

      $reflectionClass = new \ReflectionClass($class);
      $constructor = $reflectionClass->getConstructor();

      if ($constructor !== null) {

        $constructorParams = $constructor->getParameters();

        if (count($constructorParams) == 0) {
          return $reflectionClass->newInstance();
        }

        return $reflectionClass->newInstanceArgs($args);
        
      }

      return $reflectionClass->newInstanceWithoutConstructor();

    } catch (\ReflectionException $e) {
      // ignore
    }

    return null;
  }
}
