<?php

namespace TestModule\Controller;

use Hill\Module;
use Hill\Request;
use Hill\RequestMapping;
use Hill\RequestMethod;
use Hill\Response;

class TestController extends \Hill\Controller implements \Hill\IController {
    // this module provider
    private $testService_;
    // some module exported provider
    private $someService_;
    // some global exported provider
    private $someGlobalService_;
    // some vendor provider from some global imports
    private $someVendorService_;

    public function __construct(
        \TestModule\Service\TestService $testService,
        \SomeModule\Service\SomeService $someService,
        \SomeGlobalModule\Service\SomeGlobalService $someGlobalService,
        \SomeVendorModule\Service\SomeVendorService $someVendorService
    ) {
        $this->testService_ = $testService;
        $this->someService_ = $someService;
        $this->someGlobalService_ = $someGlobalService;
        $this->someVendorService_ = $someVendorService;
    }

    public static function getConfig() {
        return [
            'path' => '/',
            'mapping' => [
                new RequestMapping(RequestMethod::GET, "/", "index"),
            ],
            'middlewares' => [
                function(Module $module, Request $request) {
                    //return new Response("interrupted respose");

                    return null;
                }
            ],
            'interceptors' => [
                function(Module $module, Request $request, Response $response) {
                    // $response->clear();
                    // $response->write("This is transformed response");
                    return $response;
                }
            ]
        ];
    }

    public function index(Request $request) {
        $this->sendJson([
            //'message' => $this->testService_->sayHello(),
            //'message' => $this->someService_->sayHello(),
            //'message' => $this->someGlobalService_->sayHello(),
            'message' => $this->someVendorService_->sayHello(),
        ]);
    }
}