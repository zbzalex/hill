<?php

namespace Hill;

/**
 * Request mapping class
 */
class RequestMapping
{
    /**
     * @var string $requestMethod Request method
     */
    public $requestMethod;

    /**
     * @var string $path Path
     */
    public $path;

    /**
     * @var string $action
     */
    public $action;

    /**
     * @var IPipe[] $pipes
     */
    public $pipes;

    /**
     * @var IGuard[] $guards
     */
    public $guards;

    /**
     * 
     */
    public $middlewares;

    /**
     * 
     */
    public $interceptors;

    /**
     * 
     */
    public function __construct(
        $requestMethod,
        $path,
        $action,
        array $pipes = [],
        array $guards = [],
        array $middlewares = [],
        array $interceptors = []
    ) {
        $this->requestMethod = $requestMethod;
        $this->path = $path;
        $this->action = $action;
        $this->pipes = $pipes;
        $this->guards = $guards;
        $this->middlewares = $middlewares;
        $this->interceptors = $interceptors;
    }
}
