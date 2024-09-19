<?php

namespace Neon;

class InstanceRegistry
{
  /**
   * @var object[]
   */
  private $instances;

  public function __construct()
  {
    $this->instances = [];
  }

  /**
   * @return object|null
   */
  public function get(string $instanceClass)
  {
    return isset($this->instances[$instanceClass])
      ? $this->instances[$instanceClass]
      : null;
  }

  public function set(string $instanceClass, $instance)
  {
    $this->instances[$instanceClass] = $instance;
  }
}
