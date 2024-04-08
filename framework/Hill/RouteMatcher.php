<?php

namespace Hill;

/**
 * Router matcher class
 */
class RouteMatcher
{
    /**
     * @var Route[] List of routes
     */
    private $routes;

    /**
     * Constructor
     * 
     * @param Route[] $routes List of routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Match route
     * 
     * @param Request $request
     * 
     * @return Route|null
     */
    public function match(Request $request)
    {
        foreach ($this->routes as $route) {
            if (strpos($route->getRequestMethod(), "|") !== 1) {
                $methods = array_map(function ($method) {
                    return strtoupper(trim($method));
                }, explode("|", $route->getRequestMethod()));

                if (!in_array($request->method, $methods)) {
                    continue;
                }
            } else if ($route->getRequestMethod() != $request->method) {
                continue;
            }

            if (!preg_match($route->getCompiledPath(), $request->uri, $matches))
                continue;

            if (count($route->getArgs()) != 0) {
                foreach ($route->getArgs() as $arg) {
                    if (!isset($matches[$arg])) continue;
                    
                    $request->attributes[$arg] = $matches[$arg];
                }
            }

            return $route;
        }

        return null;
    }
}
