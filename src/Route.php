<?php

namespace Neon;

class Route
{
  /**
   * @var Module
   */
  private $module;

  /**
   * @var string
   */
  private $requestMethod;

  /**
   * @var string
   */
  private $path;

  /**
   * @var array
   */
  private $controller;

  /**
   * @var string
   */
  private $compiledPath;

  /**
   * @var array
   */
  private $args;
  
  /**
   * @var array
   */
  private $subscribedEvents;

  public function __construct(
    Module $module,
    $requestMethod,
    $path,
    $controller = null,
    array $subscribedEvents = []
  ) {
    $this->module = $module;
    $this->requestMethod = $requestMethod;
    $this->path = $path;
    $this->controller = $controller;
    $this->args = [];
    $this->subscribedEvents = $subscribedEvents;
  }

  public function compile()
  {
    $that = $this;

    $lastChar = substr($this->path, -1);
    $path = str_replace(["/", ")"], ["\/", ")?"], $this->path);

    // match params
    $path = preg_replace_callback("/@([\w]+)(:([^\/\(\)]*))?/", function ($matches) use ($that) {
      $that->args[] = $matches[1];

      if (isset($matches[3])) {
        return '(?P<' . $matches[1] . '>' . $matches[3] . ')';
      }

      return "(?P<" . $matches[1] . ">[^/\?]+)";
    }, $path);

    if ($lastChar == '/') {
      $path .= "?";
    } else {
      $path .= "\/?";
    }

    $this->compiledPath = "/"
      . "^"    // start
      . $path
      . "(?:\?.*)?"
      . "$"   // end
      . "/i"  // flags
    ;
  }

  public function getModule(): Module
  {
    return $this->module;
  }

  public function getRequestMethod(): string
  {
    return $this->requestMethod;
  }

  public function getPath(): string
  {
    return $this->path;
  }

  public function getController(): array
  {
    return $this->controller;
  }

  public function getCompiledPath(): string
  {
    return $this->compiledPath;
  }

  public function getArgs(): array
  {
    return $this->args;
  }

  public function getSubscribedEvents()
  {
    return $this->subscribedEvents;
  }
}
