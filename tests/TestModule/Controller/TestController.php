<?php

namespace TestModule\Controller;

use Hill\Request;
use Hill\RequestMapping;
use Hill\RequestMethod;
use Hill\Response;

class TestController extends \Hill\Controller implements \Hill\IController {
    public function __construct() {}

    public static function getConfig() {
        return [
            'path' => '/',
            'mapping' => [
                new RequestMapping(RequestMethod::GET, "/", "index"),
            ],
            'middlewares' => [
                function(Request $request) {
                    return new Response("hello");
                }
            ],
            'interceptors' => [
                function(Request $request, Response $response) {
                    $response->clear();
                    $response->write("This is transformed response");
                    return $response;
                }
            ]
        ];
    }

    public function index(Request $request) {
        $this->sendJson([
            'message' => 'hello!'
        ]);
    }
}