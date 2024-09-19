<?php

namespace Neon;

interface IApplicationFactory
{
  public static function create($moduleConfigOrClass, array $options = []): IApplication;
}
