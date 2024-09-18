<?php

namespace Hill;

/**
 * Registry class
 */
class InstanceRegistry
{
  /**
   * @var object[]
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
   * Get instance by class name if exists
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
