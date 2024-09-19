<?php

namespace Neon;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
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

  public function __construct(Container $container, Injector $injector, EventDispatcher $dispatcher = null)
  {
    $this->container = $container;
    $this->injector = $injector;
    $this->routeScanner = new RouteScanner($container);
    $this->routes = [];
    $this->errorHandler = function (HttpException $e) {
      $response = new Response(null);

      $response->setStatusCode($e->getCode());
      return $response;
    };
    $this->dispatcher = $dispatcher !== null ? $dispatcher : new EventDispatcher();
  }

  public function setBasePath($path)
  {
    $this->basePath = $path;
  }

  public function setErrorHandler($handler)
  {
    $this->errorHandler = $handler;
  }

  public function init()
  {
    $this->_scan();
    $this->_init();
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

    $response = null;
    $route = null;

    $matcher = new RouteMatcher($this->routes);

    try {

      $route = $matcher->match($request);
      if ($route === null)
        throw new HttpException("Not Found", 404);

      foreach (
        $route->getEvents() as $eventName => $listeners
      ) {

        foreach ($listeners as $listener) {
          $this->dispatcher->addListener($eventName, $listener);
        }
        
      }

      $controller = $route->getController();

      try {
        $reflectionClass = new \ReflectionClass($controller[0]);

        $requestEvent = new OnRequestEvent($this->injector, $request);

        $this->dispatcher->dispatch(Events::REQUEST, $requestEvent);

        if ($requestEvent->getResponse() !== null) {
          return $requestEvent->getResponse();
        }

        $response = $reflectionClass->getMethod($controller[1])
          ->invokeArgs(
            $controller[0],
            [
              $request
            ]
          );

        if (is_array($response)) {
          $response = new JsonResponse($response);
        } else if (is_scalar($response)) {
          $response = new Response($response);
        }

        $responseEvent = new OnResponseEvent($this->injector, $request, $response);

        $this->dispatcher->dispatch(Events::RESPONSE, $responseEvent);

        return $responseEvent->getResponse();
      } catch (\ReflectionException $e) {
      }
    } catch (\Exception $e) {
      return call_user_func_array($this->errorHandler, [
        $e
      ]);
    }

    return null;
  }
}
