<?php

namespace Hill;

/**
 * Web application class
 */
class WebApplication implements IApplication
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
   * Constructor
   * 
   * @param Container $container
   */
  public function __construct(Container $container)
  {
    $this->container = $container;
    $this->routeScanner = new RouteScanner($container);
    $this->routes = [];
    $this->errorHandler = function (HttpException $e) {
      $response = new Response(null);

      $response->status($e->getCode());

      return $response;
    };
  }

  /**
   * Sets base path
   * 
   * @param string $path
   */
  public function setBasePath($path)
  {
    $this->basePath = $path;
  }

  /**
   * Sets error handler
   * 
   * @param callable $handler The callback
   */
  public function setErrorHandler($handler)
  {
    $this->errorHandler = $handler;
  }
  
  public function scanForRoutes()
  {
    $this->routes = $this->routeScanner->scan($this->basePath);
  }

  /**
   * Initialization
   */
  public function init()
  {
    $this->scanForRoutes();
    $this->initializeModules();
  }

  /**
   * Process initialization modules
   */
  public function initializeModules()
  {
    $modules = array_merge($this->container->getModules(), $this->container->getGlobalModules());
    foreach ($modules as $module) {

      if (!Reflector::implementsInterface($module->getModuleClass(), IOnModuleInit::class))
        continue;

      Reflector::invokeArgs($module->getModuleClass(), "onInit", null, [
        $module
      ]);
    }
  }

  /**
   * Run the application
   */
  public function run()
  {
    $response = $this->handleRequest();
    if ($response !== null) {
      $response->send();
    }
  }

  /**
   * Handle request
   * 
   * @param Request $request Http request
   * 
   * @return Response|null
   */
  public function handleRequest(Request $request = null)
  {
    $request = $request === null
      ? Request::createFromGlobals()
      : $request;

    $requestHandler = new RequestHandler($this->routes, $this->errorHandler);

    return $requestHandler->handle($request);
  }
}
