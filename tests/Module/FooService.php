<?php

namespace Module;

class FooService implements \Hill\IInjectable
{
  public function __construct() {
  }

  public function hello()
  {
    return 'hello, world!';
  }
}
