<?php

namespace Hill;

/**
 * Request handler class
 */
class RequestHandler
{
    /**
     * @var RequestMatcher  $requestMatcher Request matcher
     */
    private $matcher;

    /**
     * @var callable        $errorHandler Invokable error handler
     */
    private $errorHandler;

    /**
     * Constructor
     * 
     * @param Route[]   $routes
     * @param callable  $errorHandler
     */
    public function __construct(array $routes, $errorHandler)
    {
        $this->matcher = new RouteMatcher($routes);
        $this->errorHandler = $errorHandler;
    }

    /**
     * Handle request
     * 
     * @param Request $request
     * 
     * @return Response|null
     */
    public function handle(Request $request)
    {
        $response = null;
        $route = null;

        try {
            $route = $this->matcher->match($request);
            if ($route === null)
                throw new HttpException("Not Found", 404);

            $controller = $route->getController();

            try {
                $reflectionClass = new \ReflectionClass($controller[0]);

                foreach ($route->getMiddlewares() as $middleware) {
                    if (($response = $middleware($route->getModule(), $request)) !== null) {
                        return $response;
                    }
                }

                $response = $reflectionClass->getMethod($controller[1])->invokeArgs($controller[0], [
                    $request
                ]);

                if (is_array($response)) {
                    $response = new JsonResponse($response);
                } else if (is_scalar($response)) {
                    $response = new Response($response);
                }

                foreach ($route->getInterceptors() as $interceptor) {
                    $response = $interceptor($route->getModule(), $request, $response);
                }

                return $response;
            } catch (\ReflectionException $e) {
                throw new \Hill\HttpException("Internal server error", 500);
            }
        } catch (\Exception $e) {
            return call_user_func_array($this->errorHandler, [
                $e
            ]);
        }

        return null;
    }
}
