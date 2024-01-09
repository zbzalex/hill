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
    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * Resolve instances
     * 
     * @param Module $module
     */
    public function resolveInstances(Module $module)
    {
        $unresolvedInstanceWrappers = array_merge(
            $module->getProviders(),
            $module->getControllers()
        );

        foreach ($unresolvedInstanceWrappers as $instanceWrapper) {
            $this->injector->resolveInstance($module, $instanceWrapper);
        }
    }
}
