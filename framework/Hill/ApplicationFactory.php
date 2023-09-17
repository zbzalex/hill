<?php

namespace Hill;

//
// Фабрика приложений.
//
class ApplicationFactory
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
