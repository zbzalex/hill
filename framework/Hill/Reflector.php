<?php

namespace Hill;

//
//
//
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
                        if (!is_string($param)) {
                            $args[] = null;
                        } else {
                            $paramClass = $param->getClass()->getName();

                            $args[] = $paramClass;
                        }
                    }

                    return $args;
                }
            }
        } catch (\ReflectionException $e) {
        }

        return [];
    }
}
