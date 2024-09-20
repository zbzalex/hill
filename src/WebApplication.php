<?php

namespace Neon;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WebApplication implements IApplication
{
  private Container $container;
  private RouteScanner $routeScanner;

  /**
   * @var string
   */
  private $basePath = "/";

  /**
   * @var Route[]
   */
  private $routes;

  private $errorHandler;

  private Injector $injector;
  private EventDispatcher $dispatcher;

  public function __construct(
    Container $container,
    Injector $injector,
    EventDispatcher $dispatcher = null,
    $errorHandler = null
  ) {
    $this->container = $container;
    $this->injector = $injector;
    $this->routeScanner = new RouteScanner($container);
    $this->routes = [];
    $this->errorHandler = $errorHandler !== null
      ? $errorHandler
      : $this->_getDefaultErrorHandler();
    $this->dispatcher = $dispatcher;
  }

  public function setBasePath($path)
  {
    $this->basePath = $path;
  }

  public function setErrorHandler($handler)
  {
    $this->errorHandler = $handler;
  }

  public function getEventDispatcher(): EventDispatcher
  {
    return $this->dispatcher;
  }

  public function init()
  {
    $this->_scan();
    $this->_init();
  }

  private function _getDefaultErrorHandler()
  {
    return function (HttpException $e) {
      $response = new Response(null);

      $response->setStatusCode($e->getCode());
      return $response;
    };
  }

  private function _scan()
  {
    $this->routes = $this->routeScanner->scan($this->basePath);
  }

  private function _init()
  {
    $modules = $this->container->getModules();
    foreach ($modules as $module) {
      if (
        Reflector::implementsInterface($module->getModuleClass(), IOnModuleInit::class)
      ) {
        Reflector::invokeArgs($module->getModuleClass(), 'onInit', null, [
          $this->injector
        ]);
      }
    }
  }

  public function run()
  {
    /** @var Response|null $response */
    $response = $this->handleRequest();
    if ($response !== null) {
      $response->send();
    }
  }
  
  public function handleRequest(Request $request = null)
  {
    $request = $request === null
      ? Request::createFromGlobals()
      : $request;
    
    $matcher = new RouteMatcher($this->routes);
    $requestHandler = new RequestHandler($matcher, $this->injector, $this->dispatcher, $this->errorHandler);
    
    return $requestHandler->handle($request);
  }
}
