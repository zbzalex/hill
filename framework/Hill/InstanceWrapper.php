<?php

namespace Hill;

//
//
//
class InstanceWrapper
{
    public $instanceClass;
    public $instance;

    public function __construct($instanceClass)
    {
        $this->instanceClass = $instanceClass;
        $this->instance = null;
    }
}
