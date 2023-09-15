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
    private $errorHandler;

    /**
     * 
     */
    public function __construct(
        Container $container
    ) {
        $this->container = $container;
        $this->routeScanner = new RouteScanner($container);
        $this->routes = [];
        $this->errorHandler = function(HttpException $e) {
            $response = new Response(null);
            
            $response->status($e->getCode());

            return $response;
        };
    }

    /**
     * 
     */
    public function setBasePath($path)
    {
        $this->basePath = $path;
    }

    public function setErrorHandler($handler) {
        $this->errorHandler = $handler;
    }

    /**
     * 
     */
    public function init()
    {
        $this->routes = $this->routeScanner->scan($this->basePath);
    }

    public function run()
    {
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

        $routeMatcher = new RouteMatcher($this->routes);

        $response = null;
        try {
            $route = $routeMatcher->match($request);

            if ($route === null)
                throw new HttpException("Not Found", 404);

            $controller = $route->getController();

            try {
                $reflectionClass = new \ReflectionClass($controller[0]);
                $reflectionMethod = $reflectionClass->getMethod($controller[1]);

                // call guards
                foreach ($route->getGuards() as $guard) {
                    if (!$guard($request)) {
                        throw new HttpException("Bad request", 400);
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
        } catch (Result $result) {
            $response = $result->getResponse();
        } catch (HttpException $e) {
            $response = call_user_func_array($this->errorHandler, [
                $e
            ]);
        }

        if ($response !== null) {
            $response->send();
        }
    }
}
