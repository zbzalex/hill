<?php

namespace Hill;

/**
 * Reflector class
 */
class Reflector
{
    /**
     * @param string $someClass
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
}
