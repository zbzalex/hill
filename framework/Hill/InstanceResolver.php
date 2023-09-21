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
     * @return InstanceWrapper[]
     */
    public function resolveGuards(array $instances)
    {
        $resolvedInstances = [];
        $guards = $this->module->getGuards();
        foreach ($instances as $instanceClass) {
            if (is_callable($instanceClass)) {
                $resolvedInstances[] = $instanceClass;
                continue;
            }

            if (isset($guards[$instanceClass]))
                continue;

            $wrapper = $this->module->addGuard($instanceClass);
            $resolvedInstances[] = $this->injector->resolveInstance($wrapper);
        }

        return $resolvedInstances;
    }

    /**
     * @param string[] $instances
     * 
     * @return InstanceWrapper[]
     */
    public function resolvePipes(array $instances)
    {
        $resolvedInstances = [];
        $pipes = $this->module->getPipes();
        foreach ($instances as $instanceClass) {
            if (is_callable($instanceClass)) {
                $resolvedInstances[] = $instanceClass;

                var_dump($instanceClass);
                continue;
            }

            if (isset($pipes[$instanceClass]))
                continue;

            $wrapper = $this->module->addPipe($instanceClass);
            $resolvedInstances[] = $this->injector->resolveInstance($wrapper);
        }

        return $resolvedInstances;
    }

    /**
     * @param string[] $instances
     * 
     * @return InstanceWrapper[]
     */
    public function resolveMiddlewares(array $instances)
    {
        $resolvedInstances = [];
        $middlewares = $this->module->getMiddlewares();
        foreach ($instances as $instanceClass) {
            if (is_callable($instanceClass)) {
                $resolvedInstances[] = $instanceClass;
                continue;
            }

            if (isset($middlewares[$instanceClass]))
                continue;

            $wrapper = $this->module->addMiddleware($instanceClass);
            $resolvedInstances[] = $this->injector->resolveInstance($wrapper);
        }

        return $resolvedInstances;
    }

    /**
     * @param string[] $instances
     * 
     * @return InstanceWrapper[]
     */
    public function resolveInterceptors(array $instances)
    {
        $resolvedInstances = [];
        $interceptors = $this->module->getInterceptors();
        foreach ($instances as $instanceClass) {
            if (is_callable($instanceClass)) {
                $resolvedInstances[] = $instanceClass;
                continue;
            }

            if (isset($interceptors[$instanceClass]))
                continue;

            $wrapper = $this->module->addInterceptor($instanceClass);
            $resolvedInstances[] = $this->injector->resolveInstance($wrapper);
        }

        return $resolvedInstances;
    }
}
