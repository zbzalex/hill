<?php

namespace Hill;

//
//
//
class RequestMapping
{
    public $requestMethod;
    public $path;
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
    public function __construct(
        $requestMethod,
        $path,
        $action,
        array $pipes = [],
        array $guards = []
    ) {
        $this->requestMethod = $requestMethod;
        $this->path = $path;
        $this->action = $action;
        $this->pipes = $pipes;
        $this->guards = $guards;
    }
}
