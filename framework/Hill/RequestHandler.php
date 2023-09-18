<?php

namespace Hill;

//
//
//
class RequestHandler
{
    private $matcher;
    private $errorHandler;

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

        try {
            // Матчинг раута
            $route = $this->matcher->match($request);

            // Если роут не найден, то выбрасываем http исключение с статусом 404
            if ($route === null)
                throw new HttpException("Not Found", 404);

            $controller = $route->getController();
            try {
                $reflectionClass = new \ReflectionClass($controller[0]);

                // Вызываем гуарды, которые есть в найденом роуте                
                foreach ($route->getGuards() as $guard) {
                    if (!$guard($request)) {
                        throw new HttpException("Bad request", 400);
                    }
                }

                // Вызываем пайпы, которые вызываны в этом роуте
                foreach ($route->getPipes() as $pipe) {
                    $pipe($request);
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
        } catch (HttpException $e) {
            // Если выброшено исключение - прокидываем его в обработчик ошибок
            $response = call_user_func_array($this->errorHandler, [
                $e
            ]);
        }

        // Если ответ был получен - отправляем его!
        if ($response !== null) {
            $response->send();
        }

        return $response;
    }
}
