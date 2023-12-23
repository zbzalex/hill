<?php

namespace Hill;

/**
 * Container compiler class
 */
class Compiler
{
    /**
     * @var string|array $moduleConfigOrClass
     */
    private $moduleConfigOrClass;

    /**
     * @var Container $container
     */
    private $container;

    /**
     * @var DependencyScanner $dependencyScanner
     */
    private $dependencyScanner;

    /**
     * @param string|array $moduleConfigOrClass
     */
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

        $modules = array_merge(
            $this->container->getModules(),
            $this->container->getGlobalModules()
        );
        
        foreach ($modules as $module) {
            $instanceResolver = new InstanceResolver($module);
            $instanceResolver->resolveInstances();
        }

        return $this->container;
    }
}
