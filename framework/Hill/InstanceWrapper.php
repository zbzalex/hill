<?php

namespace Hill;

/**
 * Instance wrapper class
 */
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

  /**
   * @var callable
   */
  public $provider = null;

  /**
   * @var string[]
   */
  public $deps = [];

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
