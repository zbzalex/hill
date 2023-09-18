<?php

namespace TestModule\Controller;

use Hill\Request;
use Hill\RequestMapping;
use Hill\RequestMethod;

class TestController extends \Hill\Controller implements \Hill\IController {
    public function __construct() {}

    public static function getConfig() {
        return [
            'path' => '/',
            'mapping' => [
                new RequestMapping(RequestMethod::GET, "/", "index"),
            ],
        ];
    }

    public function index(Request $request) {
        $this->sendJson([
            'message' => 'hello!'
        ]);
    }
}