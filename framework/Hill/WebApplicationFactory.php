<?php

namespace Hill;

/**
 * Web application factory
 */
class WebApplicationFactory
{
    /**
     * @return Application
     */
    public static function create($moduleConfigOrClass, $basePath = "/")
    {
        $compiler = new Compiler($moduleConfigOrClass);
        $container = $compiler->compile();

        $app = new Application($container);
        $app->setBasePath($basePath);
        $app->init();

        return $app;
    }
}
