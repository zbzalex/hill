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
    public static function create(ClassLoader $classLoader, $rootDir, $rootModuleConfigOrClass, $basePath = "/")
    {
        // Чтобы наше приложение заработало, нужно указать из какой папки загружать
        // классы нашего исходного кода.
        $classLoader->addFallbackDir($rootDir . "/app/");

        // create application container
        $container = new Container();
        $dependencyScanner = new DependencyScanner($container);
        $dependencyScanner->scan($rootModuleConfigOrClass);

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
