<?php

namespace Neon;

interface IModule
{
  public static function create(array $options = []): array;
}
