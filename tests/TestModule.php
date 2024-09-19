<?php

namespace Tests;

use Neon\IModule;
use Neon\Injector;
use Neon\IOnModuleInit;

class TestModule implements IModule, IOnModuleInit {
  public static function create(array $options = []): array {
    return [
      'moduleClass' => __CLASS__,
      'controllers' => [
        TestController::class,
      ],
    ];
  }

  public static function onInit(Injector $injector) {
    echo "module initialized";
  }
}