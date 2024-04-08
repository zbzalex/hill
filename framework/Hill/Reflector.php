<?php

namespace Hill;

/**
 * Reflector class
 */
class Reflector
{
    /**
     * Returns class contructor args
     * 
     * @param string $someClass
     * 
     * @return array
     */
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

    /**
     * Check implementation
     * 
     * @param string $class
     * @param string $interface
     * 
     * @return bool
     */
    public static function implementsInterface($class, $interface)
    {
        try {
            $reflector = new \ReflectionClass($class);

            return $reflector->implementsInterface($interface);
        } catch (\ReflectionException $e) {
        }

        return false;
    }

    /**
     * Invokes class method
     * 
     * @param string $class             Class
     * @param string $methodName        Method name
     * @param object|null $thisObject   This object
     * @param array $args               Invoke args
     * 
     * @return mixed|null
     */
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
}
