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
  public $subscribedEvents;

  public function __construct(
    $requestMethod,
    $path,
    $action,
    array $subscribedEvents = []
  ) {
    $this->requestMethod = $requestMethod;
    $this->path = $path;
    $this->action = $action;
    $this->subscribedEvents = $subscribedEvents;
  }
}
