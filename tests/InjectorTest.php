<?php

namespace Tests;

use Hill\Injector;
use Hill\InstanceRegistry;
use Hill\InstanceWrapper;
use PHPUnit\Framework\TestCase;

class InjectorTest extends TestCase
{
  public function testInjector()
  {
    $registry = new InstanceRegistry();
    $injector = new Injector($registry);

    $wrapper1 = new InstanceWrapper(Service1::class);
    $wrapper2 = new InstanceWrapper(Service2::class);
    $wrapper2->factory = [
      function(Connection $connection, $args) {
        var_dump($connection);
        var_dump($args);
        return new Service2();
      },
      [
        1,
        2,
        3,
      ]
    ];
    $wrapper2->deps = [
      Connection::class,
    ];


    $wrapper3 = new InstanceWrapper(Service3::class);
    $wrapper4 = new InstanceWrapper(Connection::class);

    $wrapper3->instanceClass = Service3::class;
    $wrapper3->providerFn = function ($conn) {
      return new Service3(1, $conn);
    };
    $wrapper3->deps = [
      Connection::class,
    ];

    $providers = [
      Service1::class => $wrapper1,
      Service2::class => $wrapper2,
      Service3::class => $wrapper3,
      Connection::class => $wrapper4,
    ];

    $instance = $injector->instantiate($providers, $wrapper1);
    $instance = $injector->instantiate($providers, $wrapper3);

    $this->assertTrue($instance instanceof Service3, 'Bad class');
  }
}
