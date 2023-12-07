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

    /** @var callable[] $middlewares */
    public $middlewares;
    /** @var callable[] $interceptors */
    public $interceptors;

    /**
     * Constructor
     * 
     * @param string    $requestMethod
     * @param string    $path
     * @param string    $action
     * @param array     $middlewares
     * @param array     $interceptors
     */
    public function __construct(
        $requestMethod,
        $path,
        $action,
        array $middlewares = [],
        array $interceptors = []
    ) {
        $this->requestMethod = $requestMethod;
        $this->path = $path;
        $this->action = $action;
        $this->middlewares = $middlewares;
        $this->interceptors = $interceptors;
    }
}
