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
     * @param Route[]   $routes
     * @param callable  $errorHandler
     */
    public function __construct(array $routes, $errorHandler)
    {
        $this->matcher = new RouteMatcher($routes);
        $this->errorHandler = $errorHandler;
    }

    /**
     * @param Request $request
     * 
     * @return Response|null
     */
    public function handle(Request $request)
    {
        // Ответ
        $response = null;
        $route = null;

        try {
            // Матчинг раута
            $route = $this->matcher->match($request);

            // Если роут не найден, то выбрасываем http исключение с статусом 404
            if ($route === null)
                throw new HttpException("Not Found", 404);

            $controller = $route->getController();
            try {
                $reflectionClass = new \ReflectionClass($controller[0]);

                foreach ($route->getMiddlewares() as $middleware) {
                    if (($response = $middleware($route->getModule(), $request)) !== null) {
                        throw new Result($response);
                    }
                }
                
                // Вызываем обработчик роута
                $reflectionClass->getMethod($controller[1])->invokeArgs($controller[0], [
                    $request
                ]);
            } catch (\ReflectionException $e) {
                throw new \Hill\HttpException("Internal server error", 500);
            }
        } catch (Result $result) {
            // Получим результат, который вернёт ответ
            $response = $result->getResponse();
            
            foreach ($route->getInterceptors() as $interceptor) {
                $response = $interceptor($route->getModule(), $request, $response);
            }
        } catch (\Exception $e) {
            // Если выброшено исключение - прокидываем его в обработчик ошибок
            $response = call_user_func_array($this->errorHandler, [
                $e
            ]);
        }
        
        return $response;
    }
}
