<?php

namespace Module;

class TestModule implements \Hill\IModule
{
  public static function create(array $options = []): array
  {
    return [
      'moduleClass'   => __CLASS__,
      'controllers'   => [
        \Module\TestController::class,
      ],
      'importModules' => [
        FooModule::class,
      ],
      'providers' => [
        TestService::class,
        Connection::class,
      ],
      'exports' => [
        TestService::class,
      ],
    ];
  }
}
