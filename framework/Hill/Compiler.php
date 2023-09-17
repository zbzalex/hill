<?php

namespace Hill;

//
//
//
class Compiler
{
    private $moduleConfigOrClass;
    private $container;
    private $dependencyScanner;

    public function __construct($moduleConfigOrClass) {
        $this->moduleConfigOrClass = $moduleConfigOrClass;
        $this->container = new Container();
        $this->dependencyScanner = new DependencyScanner($this->container);
    }
    
    /**
     * @return Container
     */
    public function compile()
    {
        $this->dependencyScanner->scan($this->moduleConfigOrClass);

        $modules = $this->container->getModules();
        foreach ($modules as $module) {
            // resolve module instances
            $instanceResolver = new InstanceResolver($module);
            $instanceResolver->resolveInstances();
        }

        return $this->container;
    }
}
