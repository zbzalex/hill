<?php

namespace Hill;

/**
 * Web application factory
 */
class WebApplicationFactory implements IApplicationFactory
{
    /**
     * @return WebApplication
     */
    public static function create($moduleConfigOrClass, $basePath = "/")
    {
        $compiler = new Compiler($moduleConfigOrClass);
        $container = $compiler->compile();

        $app = new WebApplication($container);
        $app->setBasePath($basePath);
        $app->init();

        return $app;
    }
}
