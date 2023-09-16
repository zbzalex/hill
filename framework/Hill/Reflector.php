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
                        
                        if (version_compare(phpversion(), '5.6.0', '>')) {
                            $args[] = $param->getType()->getName();
                        } else {
                            $args[] = $param->getClass()->getName();
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
