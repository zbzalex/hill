<?php

namespace Hill;

/**
 * Router clanner class
 */
class RouteScanner
{
  /**
   * @var Container
   */
  private $container;

  /**
   * @var Route[]
   */
  private $routes;

  /**
   * Constructor
   * 
   * @param Container $container Module container
   */
  public function __construct(Container $container)
  {
    $this->container = $container;
    $this->routes = [];
  }

  /**
   * Scanning routes
   * 
   * @param string $basePath Base path
   * 
   * @return Route[]
   */
  public function scan($basePath)
  {
    $basePath = rtrim($basePath, '/') . "/";

    $modules = $this->container->getModules();

    foreach ($modules as $module) {

      $controllers = $module->getControllers();
      foreach ($controllers as $wrapper) {
        try {
          $reflectionClass = new \ReflectionClass($wrapper->instanceClass);
          $config = $reflectionClass->getMethod('getConfig')->invoke(null);

          $controllerBasePath = isset($config['path'])
            ? $config['path']
            : "/";
          $controllerBasePath = trim($controllerBasePath, '/') . "/";
          $mapping = isset($config['mapping'])
            ? $config['mapping']
            : [];
          $middlewares = isset($config['middlewares'])
            ? $config['middlewares']
            : [];
          $interceptors = isset($config['interceptors'])
            ? $config['interceptors']
            : [];

          $path = rtrim($basePath . $controllerBasePath, "/") . "/";

          $this->registerRoutes(
            $module,
            $wrapper,
            $path,
            $mapping,
            $middlewares,
            $interceptors
          );
        } catch (\ReflectionException $e) {
        }
      }
    }

    return $this->routes;
  }

  /**
   * Register routes
   * 
   * @param InstanceResolver $instanceResolver
   */
  private function registerRoutes(
    Module $module,
    $wrapper,
    $basePath,
    array $mapping,
    array $middlewares,
    array $interceptors
  ) {
    foreach ($mapping as $map) {
      /** @var RequestMapping $map */

      $path = $basePath . trim($map->path, '/');
      if ($path != "/") {
        $path = rtrim($path, "/");
      }

      $route = new Route(
        $module,
        $map->requestMethod,
        $path,
        [
          $wrapper->instance,
          $map->action
        ],
        array_merge(
          $middlewares,
          $map->middlewares,
        ),
        array_merge(
          $interceptors,
          $map->interceptors,
        )
      );

      // compile pattern
      $route->compile();

      $this->routes[] = $route;
    }
  }
}
