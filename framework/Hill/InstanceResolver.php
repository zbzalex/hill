<?php

namespace Hill;

/**
 * Instance resolver
 */
class InstanceResolver
{
    /** @var Injector $injector */
    private $injector;

    /**
     * Constructor
     * 
     * @param Injector $injector
     */
    public function __construct(Injector $injector = null)
    {
        $this->injector = $injector !== null
            ? $injector
            : new Injector();
    }

    /**
     * Resolve module instances
     * 
     * @param Module $module The module
     */
    public function resolveModuleInstances(Module $module)
    {
        $instanceWrappers = array_merge($module->getProviders(), $module->getControllers());
        foreach ($instanceWrappers as $instanceWrapper) {
            $this->injector->resolveInstance($module, $instanceWrapper);
        }
    }
}
