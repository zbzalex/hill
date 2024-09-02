<?php

namespace Hill;

/**
 * Container builder class
 */
class ContainerBuilder
{
    /** @var string|array $moduleConfigOrClass */
    private $moduleConfigOrClass;
    /** @var Container $container */
    private $container;
    /** @var Scanner $scanner */
    private $scanner;
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
        $this->scanner = new Scanner($this->container);
        $this->instanceResolver = new InstanceResolver();
    }

    /**
     * Build container modules and they dependencies
     * 
     * @return Container
     */
    public function build()
    {
        // scan module for dependencies
        $this->scanner->scan($this->moduleConfigOrClass);
        
        $modules = $this->container->getModules();
        foreach ($modules as $module) {
            $this->instanceResolver->instantiateInjectables($module);
        }

        return $this->container;
    }
}
