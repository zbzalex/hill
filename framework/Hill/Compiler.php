<?php

namespace Hill;

/**
 * Container compiler class
 */
class Compiler
{
    /** @var string|array $moduleConfigOrClass */
    private $moduleConfigOrClass;
    /** @var Container $container */
    private $container;
    /** @var DependencyScanner $dependencyScanner */
    private $dependencyScanner;
    /** @var InstanceResolver $instanceResolver */
    private $instanceResolver;

    /**
     * Constructor
     * 
     * @param array|string $moduleConfigOrClass Module config or module class
     */
    public function __construct($moduleConfigOrClass)
    {
        $this->moduleConfigOrClass = $moduleConfigOrClass;
        $this->container = new Container();
        $this->dependencyScanner = new DependencyScanner($this->container);
        $this->instanceResolver = new InstanceResolver();
    }

    /**
     * Compile container modules and they dependencies
     * 
     * @return Container
     */
    public function compile()
    {
        // scan module for dependencies
        $this->dependencyScanner->scan($this->moduleConfigOrClass);
        
        $modules = array_merge($this->container->getModules(), $this->container->getGlobalModules());
        foreach ($modules as $module) {
            $this->instanceResolver->processModuleServicesInstantiation($module);
        }

        return $this->container;
    }
}
