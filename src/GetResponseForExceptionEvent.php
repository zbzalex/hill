<?php

namespace Neon;

use Symfony\Component\HttpFoundation\Request;

class GetResponseForExceptionEvent extends GetResponseEvent
{
  protected $exception;

  public function __construct(\Exception $exception, Request $request, Injector $injector)
  {
    $this->exception = $exception;
    
    parent::__construct($injector, $request);
  }

  public function getException(): \Exception
  {
    return $this->exception;
  }
}
