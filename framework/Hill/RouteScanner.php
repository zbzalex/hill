<?php

namespace Hill;

//
//
//
class RouteScanner
{
    /**
     * @var Container $container
     */
    private $container;

    /**
     * @var Route[] $routes
     */
    private $routes;

    /**
     * 
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->routes = [];
    }

    /**
     * 
     */
    public function scan($basePath)
    {
        // базовый путь приложения всегда должен содержать слэш в конце
        $basePath = rtrim($basePath, '/') . "/";

        $modules = $this->container->getModules();
        foreach ($modules as $module) {
            $instanceResolver = new InstanceResolver($module);

            $controllers = $module->getControllers();
            foreach ($controllers as $wrapper) {
                try {
                    $reflectionClass = new \ReflectionClass($wrapper->instanceClass);
                    $reflectionMethod = $reflectionClass->getMethod('path');
                    $controllerBasePath = $reflectionMethod->invoke(null);

                    // убираем с обеих сторон слэш т.к в базовом пути приложения он
                    // всегда справа есть и добавляем его справа
                    $controllerBasePath = trim($controllerBasePath, '/') . "/";

                    $reflectionMethod = $reflectionClass->getMethod('routes');
                    $mapping = $reflectionMethod->invoke(null);

                    $path = rtrim($basePath . $controllerBasePath, "/") . "/";

                    $this->registerRoutes($instanceResolver, $wrapper, $path, $mapping);
                } catch (\ReflectionException $e) {
                }
            }
        }

        return $this->routes;
    }

    /**
     * @param InstanceResolver $instanceResolver
     */
    private function registerRoutes($instanceResolver, $wrapper, $basePath, array $mapping)
    {
        foreach ($mapping as $map) {

            $path = $basePath . trim($map->path, '/');
            if ($path != "/") {
                $path = rtrim($path, "/");
            }

            $route = new Route(
                $map->requestMethod,
                $path,
                [
                    $wrapper->instance,
                    $map->action
                ],
                $instanceResolver->registerAndResolveUnresolvedInstances($map->pipes),
                $instanceResolver->registerAndResolveUnresolvedInstances($map->guards)
            );

            $route->compile();

            $this->routes[] = $route;
        }
    }
}
