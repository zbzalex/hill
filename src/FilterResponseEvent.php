<?php

namespace Neon;

class FilterResponseEvent extends GetResponseEvent
{
  public function __construct(Request $request, Response $response, Injector $injector)
  {
    parent::__construct($request, $injector);

    $this->response = $response;
  }
}
