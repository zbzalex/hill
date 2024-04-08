<?php

namespace Hill;

/**
 * Instance wrapper class
 */
class InstanceWrapper
{
    /**
     * @var string $instanceClass
     */
    public $instanceClass;

    /**
     * @var object|null $instance
     */
    public $instance;

    /**
     * @var array|null $factory
     */
    public $factory;

    /**
     * Constructor
     * 
     * @param string     $instanceClass Instance class
     * @param array|null $factory       Factory
     */
    public function __construct($instanceClass, $factory = null)
    {
        $this->instanceClass = $instanceClass;
        $this->instance = null;
        $this->factory = $factory;
    }
}
