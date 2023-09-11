<?php

namespace Hill;

//
// Приложение.
//
class Application
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var RouteScanner $routeScanner
     */
    private $routeScanner;

    /**
     * @var string $basePath
     */
    private $basePath = "/";

    /**
     * @var Route[] $routes
     */
    private $routes;

    /**
     * 
     */
    public function __construct(
        Container $container
    ) {
        $this->container = $container;
        $this->routeScanner = new RouteScanner($container);
        $this->routes = [];
    }

    /**
     * 
     */
    public function setBasePath($path)
    {
        $this->basePath = $path;
    }

    /**
     * 
     */
    public function init()
    {
        $this->routes = $this->routeScanner->scan($this->basePath);
    }

    public function run() {
        $this->handleRequest();
    }

    /**
     * 
     */
    public function handleRequest(Request $request = null)
    {
        // create request
        $request = $request === null
            ? Request::createFromGlobals()
            : $request;

        $response = null;
        try {

            // match route

            foreach ($this->routes as $route) {
                if ($route->getRequestMethod() != $request->method)
                    continue;

                if (!preg_match($route->getCompiledPath(), $request->uri, $matches))
                    continue;

                if (count($route->getArgs()) != 0) {
                    foreach ($route->getArgs() as $arg) {
                        $request->attributes[$arg] = $matches[$arg];
                    }
                }

                $controller = $route->getController();
                
                try {
                    $reflectionClass = new \ReflectionClass($controller[0]);
                    $reflectionMethod = $reflectionClass->getMethod($controller[1]);
                    
                    // call guards
                    foreach ($route->getGuards() as $guard) {
                        if (!$guard($request)) {
                            throw new Result(new Response(null));
                        }
                    }

                    // call pipes
                    foreach ($route->getPipes() as $pipe) {
                        $pipe($request);
                    }

                    $reflectionMethod->invokeArgs($controller[0], [
                        $request
                    ]);
                } catch (\ReflectionException $e) {
                }
            }
        } catch (Result $result) {
            $response = $result->getResponse();
        }

        if ($response !== null) {
            $response->send();
        }
    }
}
