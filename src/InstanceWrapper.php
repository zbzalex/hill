<?php

namespace Neon;

class InstanceWrapper
{
  /**
   * @var string
   */
  public $instanceClass;

  /**
   * @var object|null
   */
  public $instance;

  /**
   * @var array|null
   */
  public $factory;
  
  public $provider = null;

  /**
   * @var string[]
   */
  public $inject = [];

  public function __construct($instanceClass, $factory = null)
  {
    $this->instanceClass = $instanceClass;
    $this->instance = null;
    $this->factory = $factory;
  }
}
