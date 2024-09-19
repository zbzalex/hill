<?php

namespace Neon;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OnResponseEvent extends Event
{
  private $injector;
  private $request;
  private $response;

  public function __construct(Injector $injector, Request $request, Response $response)
  {
    $this->injector = $injector;
    $this->request = $request;
    $this->response = $response;
  }

  public function getInjector(): Injector
  {
    return $this->injector;
  }

  public function getRequest(): Request
  {
    return $this->request;
  }

  public function setResponse(Response $response)
  {
    $this->response = $response;
  }

  public function getResponse()
  {
    return $this->response;
  }
}
