<?php

namespace Hill;

/**
 * Instance resolver
 */
class InstanceResolver
{
    /**
     * @var Module $module The module
     */
    private $module;

    /**
     * @var Injector $injector Dependency injector
     */
    private $injector;

    /**
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
        $this->injector = new Injector($module);
    }

    public function resolveInstances()
    {
        $providers      = $this->module->getProviders();
        $controllers    = $this->module->getControllers();

        foreach ($providers as $provider) {
            $this->injector->resolveInstance($provider);
        }

        foreach ($controllers as $controller) {
            $this->injector->resolveInstance($controller);
        }
    }
}
