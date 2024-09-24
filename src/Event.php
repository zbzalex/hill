<?php

namespace Neon;

abstract class Event
{
  protected $prevented;

  public function __construct()
  {
    $this->prevented = false;
  }

  public function isPrevented()
  {
    return $this->prevented;
  }

  public function prevent()
  {
    $this->prevented = true;
  }
}
