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
    public function registerAndResolveInstances(array $instances)
    {
        $resolvedInstances = [];

        // $moduleInstances = $this->module->getInstances();
        
        foreach ($instances as $instanceClass) {
            if (!is_string($instanceClass)) {
                if (is_callable($instanceClass)) {
                    $resolvedInstances[] = $instanceClass;
                }
                continue;
            }

            // if (isset($moduleInstances[$instanceClass]))
            //     continue;

            $this->module->addInstance($instanceClass);
        }

        // resolve module instances
        foreach ($this->module->getInstances() as $wrapper) {
            $resolvedInstances[] = $this->injector->resolveInstance($wrapper);
        }

        return $resolvedInstances;
    }
}
