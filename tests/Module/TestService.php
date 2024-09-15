<?php

namespace Module;

class TestService implements \Hill\IInjectable
{
  private $fooService;
  public function __construct(
    FooService $fooService,
    Connection $connection
  )
  {
    $this->fooService = $fooService;
  }
  public function test()
  {
    return $this->fooService->hello();
  }
}
