<?php

namespace Neon;

class RequestHandler
{
  private $matcher;
  private $injector;
  private $dispatcher;

  public function __construct(
    RouteMatcher $matcher,
    Injector $injector,
    EventDispatcher $dispatcher
  ) {
    $this->matcher = $matcher;
    $this->injector = $injector;
    $this->dispatcher = $dispatcher;
  }

  public function handle(Request $request)
  {
    $response = null;
    $route = null;

    try {

      $route = $this->matcher->match($request);
      if ($route === null)
        throw new HttpException("Not Found", 404);
                  
      foreach (
        $route->getSubscribedEvents() as $eventName => $listeners
      ) {
        foreach ($listeners as $listener) {
          $this->dispatcher->addListener($eventName, $listener);
        }
      }

      $controller = $route->getController();
      $event = new GetResponseEvent($request, $this->injector);
      $this->dispatcher->dispatch(LifecycleEvents::REQUEST, $event);
      if ($event->hasResponse()) {
        return $event->getResponse();
      }

      $response = Reflector::invokeArgs(
        get_class($controller[0]),
        $controller[1],
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

      $event = new FilterResponseEvent($request, $response, $this->injector);
      $this->dispatcher->dispatch(LifecycleEvents::RESPONSE, $event);

      if (!$event->hasResponse())
        throw new \Exception(
          sprintf(
            "Are your remember to send response?"
          )
        );

      return $event->getResponse();
    } catch (\Exception $e) {

      $event = new GetResponseForExceptionEvent($e, $request, $this->injector);
      $this->dispatcher->dispatch(LifecycleEvents::EXCEPTION, $event);

      if ($event->hasResponse()) {
        return $event->getResponse();
      }
    }

    return null;
  }
}
