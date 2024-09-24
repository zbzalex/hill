<?php

namespace Neon;

class GetResponseForExceptionEvent extends GetResponseEvent
{
  protected $exception;

  public function __construct(\Exception $exception, Request $request, Injector $injector)
  {
    $this->exception = $exception;
    
    parent::__construct($request, $injector);
  }

  public function getException(): \Exception
  {
    return $this->exception;
  }
}
