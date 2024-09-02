<?php

namespace Tests;

use Hill\ContainerBuilder;
use Hill\Module;
use Hill\Request;
use Hill\RequestMethod;
use Hill\Route;
use Hill\RouteMatcher;
use Hill\RouteScanner;
use Hill\Validator;

/**
 * Module test class
 */
class ModuleTest extends \PHPUnit\Framework\TestCase
{
    public function testModule()
    {
        /** @var \Hill\Container $container */
        $container = \Hill\Test::createTestModule(\Module\TestModule::class);

        // scan routes
        $routeScanner = new RouteScanner($container);
        $routes = $routeScanner->scan("/");

        // create request
        $request = new Request(RequestMethod::GET, "/hello");

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

        $this->assertNotNull($response, "Response is null");

        // if ($response !== null) {
        
        // Creates output buffer
        ob_start();

        $response->send();

        $content = ob_end_clean(); // closing and put to $content

        $this->assertEquals($response->status(), 200, sprintf("Response code: %d\n", $response->status()));
        $this->assertEquals($content, "hello", "Response send something wrong");
        // }
    }

    public function _testContainerBuilder() {
      
      $containerBuilder = new ContainerBuilder(
        \Module\TestModule::create(),
      );

      $container = $containerBuilder->build();



    }
}
