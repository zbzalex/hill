<?php

namespace Module;

class FooModule implements \Hill\IModule
{
  public static function create(array $options = []): array
  {
    return [
      'moduleClass'   => __CLASS__,
      'providers'   => [
        \Module\FooService::class,
      ],
      'exports' => [
        \Module\FooService::class,
      ],
    ];
  }
}
