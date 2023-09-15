<?php

namespace Hill;

//
//
//
class InstanceWrapper
{
    public $instanceClass;
    public $instance;
    public $factory;

    public function __construct($instanceClass, $factory = null)
    {
        $this->instanceClass = $instanceClass;
        $this->instance = null;
        $this->factory = $factory;
    }
}
