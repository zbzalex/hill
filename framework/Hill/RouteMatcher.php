<?php

namespace Hill;

/**
 * Router matcher class
 */
class RouteMatcher
{
    private $routes;
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
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
                    $request->attributes[$arg] = $matches[$arg];
                }
            }

            return $route;
        }

        return null;
    }
}
