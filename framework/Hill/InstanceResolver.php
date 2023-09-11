<?php

namespace Hill;

//
//
//
class InstanceResolver
{
    private $module;
    private $injector;

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

    /**
     * @param string[] $instances
     * 
     * @return mixed[]
     */
    public function registerAndResolveUnresolvedInstances(array $unresolvedInstances)
    {
        $resolvedInstances = [];

        $unresolvedInstances_ = $this->module->getUnresolvedInstances();
        foreach ($unresolvedInstances as $unresolvedClass) {

            if (!is_string($unresolvedClass)) {
                
                if (is_callable($unresolvedClass)) {
                    $resolvedInstances[] = $unresolvedClass;
                }

                continue;
            }

            if (isset($unresolvedInstances_[$unresolvedClass]))
                continue;

            $this->module->addUnresolvedInstance($unresolvedClass);
        }

        foreach ($this->module->getUnresolvedInstances() as $wrapper) {
            $resolvedInstances[] = $this->injector->resolveInstance($wrapper);
        }

        return $resolvedInstances;
    }
}
