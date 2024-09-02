<?php

namespace Module;

class FooService implements \Hill\IInjectable
{
  public function __construct(\Module\TestService $testService) {
    // var_dump($testService->test());
  }

  public function hello()
  {
    return 'hello, world!';
  }
}
