<?php

namespace Neon;

use Symfony\Component\EventDispatcher\EventDispatcher;

class WebApplicationFactory implements IApplicationFactory
{
  public static function create($moduleConfigOrClass, array $options = []): IApplication
  {
    $injector = new Injector();
    $containerBuilder = new ContainerBuilder($moduleConfigOrClass, $injector);
    $container = $containerBuilder->build();

    $dispatcher = new EventDispatcher();

    $app = new WebApplication($container, $injector, $dispatcher);

    $basePath = isset($options['basePath']) ? $options['basePath'] : '/';

    $app->setBasePath($basePath);
    $app->init();
    
    return $app;
  }
}
