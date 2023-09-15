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
    public static function create($rootModuleConfigOrClass, $basePath = "/")
    {
        $container = new Container();
        $dependencyScanner = new DependencyScanner($container);
        $dependencyScanner->scan($rootModuleConfigOrClass);

        $modules = $container->getModules();
        foreach ($modules as $module) {
            $instanceResolver = new InstanceResolver($module);
            $instanceResolver->resolveInstances();
        }

        $app = new Application($container);
        $app->setBasePath($basePath);
        $app->init();

        return $app;
    }
}
