<?php

namespace Tests;

use Neon\Injector;
use Neon\InstanceRegistry;
use Neon\InstanceWrapper;
use PHPUnit\Framework\TestCase;

class InjectorTest extends TestCase
{
  public function testInjector() {

    $injector = new Injector(new InstanceRegistry());

    $wrapper1 = new InstanceWrapper('\Acme\Service1');
    $wrapper2 = new InstanceWrapper('\Acme\Service2');
    
  }
}
