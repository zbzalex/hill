<?php

namespace Hill;

/**
 * Route data object class
 */
class Route
{
    private $module;

    /**
     * @var string $requestMethod Request method
     */
    private $requestMethod;

    /**
     * @var string $path Path
     */
    private $path;

    /**
     * 
     */
    private $controller;

    /**
     * 
     */
    private $compiledPath;

    /**
     * 
     */
    private $args;


    /**
     * 
     */
    private $middlewares;

    /**
     * 
     */
    private $interceptors;

    /**
     * 
     */
    public function __construct(
        Module $module,
        $requestMethod,
        $path,
        $controller,
        $middlewares,
        $interceptors
    ) {
        $this->module = $module;
        $this->requestMethod = $requestMethod;
        $this->path = $path;
        $this->controller = $controller;
        $this->args = [];
        $this->middlewares = $middlewares;
        $this->interceptors = $interceptors;
    }

    /**
     * 
     */
    public function compile()
    {
        $that = $this;

        $path = str_replace("/", "\/", $this->path) . "\/?";
        
        // match params
        $path = preg_replace_callback("/:(\w+)/", function ($matches) use ($that) {
            $that->args[] = $matches[1];
            return "(?P<" . $matches[1] . ">[a-z0-9_]+)";
        }, $path);

        $path = "/"
            . "^"    // start
            . $path
            . "(?:\?.*)?"
            . "$"   // end
            . "/i"  // flags
        ;

        $this->compiledPath = $path;
    }

    public function getModule() { return $this->module; }

    /**
     * 
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * 
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * 
     */
    public function getCompiledPath()
    {
        return $this->compiledPath;
    }

    /**
     * 
     */
    public function getArgs()
    {
        return $this->args;
    }

    public function getMiddlewares() {
        return $this->middlewares;
    }

    public function getInterceptors() {
        return $this->interceptors;
    }
}
