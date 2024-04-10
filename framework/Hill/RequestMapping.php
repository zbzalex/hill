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
     * @var string $path Route path
     */
    public $path;

    /**
     * @var string $action Controller
     */
    public $action;

    /**
     * @var IMiddleware[] $middlewares List of middlewares
     */
    public $middlewares;

    /**
     * @var IInterceptor[] $interceptors List of interceptors
     */
    public $interceptors;

    /**
     * Constructor
     * 
     * @param string    $requestMethod  Request method
     * @param string    $path           Route path
     * @param string    $action         Controller
     * @param array     $middlewares    List of middlewares
     * @param array     $interceptors   List of interceptors
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
