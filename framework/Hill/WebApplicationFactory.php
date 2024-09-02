<?php

namespace Hill;

/**
 * Web application factory
 */
class WebApplicationFactory implements IApplicationFactory
{
    /**
     * Creates a new application
     * 
     * @param string|array $moduleConfigOrClass Module config or module class
     * @param array $options Options
     * 
     * @return WebApplication
     */
    public static function create($moduleConfigOrClass, array $options = []): IApplication
    {
        $containerBuilder = new ContainerBuilder($moduleConfigOrClass);
        $container = $containerBuilder->build();

        $app = new WebApplication($container);

        $basePath = isset($options['basePath']) ? $options['basePath'] : '/';
        
        $app->setBasePath($basePath);
        $app->init();

        return $app;
    }
}
