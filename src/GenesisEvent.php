<?php

namespace Neon;

class GenesisEvent extends Event
{
  protected $request;
  protected $injector;

  public function __construct(Request $request, Injector $injector)
  {
    $this->request = $request;
    $this->injector = $injector;
  }

  public function getRequest(): Request
  {
    return $this->request;
  }

  public function getInjector(): Injector
  {
    return $this->injector;
  }
}
