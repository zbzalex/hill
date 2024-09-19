<?php

namespace Neon;

class RequestMapping
{
  /**
   * @var string
   */
  public $requestMethod;

  /**
   * @var string
   */
  public $path;

  /**
   * @var string
   */
  public $action;

  /**
   * @var array
   */
  public $events;

  public function __construct(
    $requestMethod,
    $path,
    $action,
    array $events = []
  ) {
    $this->requestMethod = $requestMethod;
    $this->path = $path;
    $this->action = $action;
    $this->events = $events;
  }
}
