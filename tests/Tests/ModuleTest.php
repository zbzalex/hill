<?php

namespace Tests;

use Hill\Module;
use Hill\Request;
use Hill\RequestMethod;
use Hill\Route;
use Hill\RouteMatcher;
use Hill\RouteScanner;
use Hill\Validator;
use TestModule\TestModule;

/**
 * Module test class
 */
class ModuleTest extends \PHPUnit\Framework\TestCase
{
    public function testModule()
    {
        // /** @var \Hill\Container $container */
        // $container = \Hill\Test::createTestModule(\TestModule\TestModule::class);
        // /** @var \Hill\Module $testModule */
        // $testModule = $container->getModule(\TestModule\TestModule::class);

        // // /** @var \TestModule\Service\TestProvider $testService */
        // // $testService = $testModule[\TestModule\Service\TestService::class];
        // // echo $testService->sayHello();

        // // scan routes
        // $routeScanner = new RouteScanner($container);
        // $routes = $routeScanner->scan("/");

        // // create request
        // $request = new Request(RequestMethod::GET, "/someGlobalModule");

        // // handle request and send response
        // $requestHandler = new \Hill\RequestHandler($routes, function (\Exception $e) {
        //     if ($e instanceof \Hill\HttpException) {
        //         // TODO: ..
        //     }
        //     $response = new \Hill\Response(null);
        //     $response->status($e->getCode());
        //     return $response;
        // });

        // $response = $requestHandler->handle($request);
        // if ($response !== null) {
        //     $response->send();
        // }

        // echo $response->status() . "\n";
    }

    public function testValidator()
    {
        // $validator = new Validator([
        //     'name' => [
        //         //// string required
        //         function ($field, $value) {
        //             return is_string($value)
        //                 ? null
        //                 : sprintf("Значение %s не является строковым значением", $field);
        //         },
        //         //// isnt empty
        //         function ($field, $value) {
        //             return is_string($value) && mb_strlen($value) < 1
        //                 ? sprintf("Значение %s не указано", $field)
        //                 : null;
        //         },
        //     ],
        // ]);

        // $errors = $validator->validate([
        //     'name' => "",
        // ]);

        // $this->assertTrue(count($errors) == 0, "Ошибка валидации");
    }

    public function testRoute()
    {
        $req = new Request("GET", "/333KZO");
        
        $route = new Route(new Module(TestModule::class), "GET", "/@username:[0-9a-z_]+", null);
        $route->compile();

        $matcher = new RouteMatcher([
            $route
        ]);

        var_dump($matcher->match($req));
    }
}
