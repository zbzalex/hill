<?php

namespace Tests;

use Hill\Request;
use Hill\RequestMethod;
use Hill\RouteScanner;

/**
 * Module test class
 */
class ModuleTest extends \PHPUnit\Framework\TestCase
{
    public function testModule()
    {
        /** @var \Hill\Container $container */
        $container = \Hill\Test::createTestModule(\TestModule\TestModule::class);
        /** @var \Hill\Module $testModule */
        $testModule = $container->getModule(\TestModule\TestModule::class);

        // /** @var \TestModule\Service\TestProvider $testService */
        // $testService = $testModule[\TestModule\Service\TestService::class];
        // echo $testService->sayHello();

        // scan routes
        $routeScanner = new RouteScanner($container);
        $routes = $routeScanner->scan("/");

        // create request
        $request = new Request(RequestMethod::GET, "/someGlobalModule");
	
        // handle request and send response
        $requestHandler = new \Hill\RequestHandler($routes, function (\Exception $e) {
            if ($e instanceof \Hill\HttpException) {
                // TODO: ..
            }
            $response = new \Hill\Response(null);
            $response->status($e->getCode());
            return $response;
        });

        $response = $requestHandler->handle($request);
        if ($response !== null) {
            $response->send();
        }
        
        echo $response->status() . "\n";
    }
}
