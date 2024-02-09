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
        $controller = null,
        array $middlewares = [],
        array $interceptors = []
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
     * Compile regex string
     */
    public function compile()
    {
        $that = $this;

        $lastChar = substr($this->path, -1);
        $path = str_replace(["/", ")"], ["\/", ")?"], $this->path);

        // match params
        $path = preg_replace_callback("/@([\w]+)(:([^\/\(\)]*))?/", function ($matches) use ($that) {
            $that->args[] = $matches[1];

            if (isset($matches[3])) {
                return '(?P<' . $matches[1] . '>' . $matches[3] . ')';
            }

            return "(?P<" . $matches[1] . ">[^/\?]+)";
        }, $path);

        if ($lastChar == '/') {
            $path .= "?";
        } else {
            $path .= "\/?";
        }

        $this->compiledPath = "/"
            . "^"    // start
            . $path
            . "(?:\?.*)?"
            . "$"   // end
            . "/i"  // flags
        ;
    }

    public function getModule()
    {
        return $this->module;
    }

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
     * Returns compiled regex string
     * 
     * @return string
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

    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    public function getInterceptors()
    {
        return $this->interceptors;
    }
}
