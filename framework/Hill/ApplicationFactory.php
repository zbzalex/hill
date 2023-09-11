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
    public static function create($moduleClass, $basePath = "/")
    {
        // create application container
        $container = new Container();
        $dependencyScanner = new DependencyScanner($container);
        $dependencyScanner->scan($moduleClass);

        // instantiate modules dependencies
        $modules = $container->getModules();
        foreach ($modules as $module) {
            $instanceResolver = new InstanceResolver($module);
            $instanceResolver->resolveInstances();
        }
        
        // create application
        $app = new Application($container);
        $app->setBasePath($basePath);
        $app->init();

        return $app;
    }
}
