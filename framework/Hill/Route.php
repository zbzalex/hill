<?php

namespace Hill;

/**
 * Route data object class
 */
class Route
{
  /**
   * @var Module $module
   */
  private $module;

  /**
   * @var string $requestMethod Request method
   */
  private $requestMethod;

  /**
   * @var string $path Path
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
   * @var array $args
   */
  private $args;


  /**
   * @var Middleware[] $middlewares
   */
  private $middlewares;

  /**
   * @var Interceptor[] $incerceptors
   */
  private $interceptors;

  /**
   * Contructor
   * 
   * @param Module $module
   * @param string $requestMethod
   * @param string $path
   * @param array $controller
   * @param Middleware[] $middlewares
   * @param Interceptor[] $interceptors
   */
  public function __construct(
    Module $module,
    $requestMethod,
    $path,
    $controller = null,
    array $middlewares = [],
    array $interceptors = []
  ) {
    $this->module = $module;
    $this->requestMethod = $requestMethod;
    $this->path = $path;
    $this->controller = $controller;
    $this->args = [];
    $this->middlewares = $middlewares;
    $this->interceptors = $interceptors;
  }

  /**
   * Compile regex string
   */
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

  /**
   * Returns module
   * 
   * @return Module
   */
  public function getModule(): Module
  {
    return $this->module;
  }

  /**
   * Returns request method
   * 
   * @return string
   */
  public function getRequestMethod(): string
  {
    return $this->requestMethod;
  }

  /**
   * Returns route path
   * 
   * @return string
   */
  public function getPath(): string
  {
    return $this->path;
  }

  /**
   * Returns route controller
   * 
   * @return array
   */
  public function getController(): array
  {
    return $this->controller;
  }

  /**
   * Returns compiled regex string
   * 
   * @return string
   */
  public function getCompiledPath(): string
  {
    return $this->compiledPath;
  }

  /**
   * Returns request args
   * 
   * @return array
   */
  public function getArgs(): array
  {
    return $this->args;
  }

  /**
   * Returns route middlewares
   * 
   * @return Middleware[]
   */
  public function getMiddlewares()
  {
    return $this->middlewares;
  }

  /**
   * Returns route interceptors
   * 
   * @return Interceptor[]
   */
  public function getInterceptors()
  {
    return $this->interceptors;
  }
}
