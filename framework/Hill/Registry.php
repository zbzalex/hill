<?php

namespace Hill;

/**
 * Registry class
 */
class Registry
{
    /**
     * @var object[] $instances
     */
    private $instances;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->instances = [];
    }

    /**
     * Get instance
     * 
     * @param string $instanceClass
     * 
     * @return object|null
     */
    public function get($instanceClass)
    {
        return isset($this->instances[$instanceClass])
            ? $this->instances[$instanceClass]
            : null;
    }

    /**
     * Set instance
     * 
     * @param string $instanceClass
     * @param object $instance
     */
    public function set($instanceClass, $instance)
    {
        $this->instances[$instanceClass] = $instance;
    }
}
