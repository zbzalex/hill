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
     * @var callable $errorHandler
     */
    private $errorHandler;

    /**
     * @param Container $container
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
     * @param string $path
     */
    public function setBasePath($path)
    {
        $this->basePath = $path;
    }

    /**
     * @param callable $handler
     */
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

    /**
     * 
     */
    public function run()
    {
        $this->handleRequest();
    }

    /**
     * @param Request $request
     * 
     * @return Response|null
     */
    public function handleRequest(Request $request = null)
    {
        // Если запрос не был передан как 1 агрумент, то создём его из глобыльных
        // переменных.
        $request = $request === null
            ? Request::createFromGlobals()
            : $request;

        $requestHandler = new RequestHandler($this->routes, $this->errorHandler);

        $response = $requestHandler->handle($request);
        if ($response !== null) {
            $response->send();
        }
    }
}
