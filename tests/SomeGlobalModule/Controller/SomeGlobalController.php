<?php

namespace SomeGlobalModule\Controller;

use Hill\Request;
use Hill\RequestMapping;
use Hill\RequestMethod;

class SomeGlobalController extends \Hill\Controller implements \Hill\IController {
    public static function getConfig() {
        return [
            'path' => '/someGlobalModule',
            'mapping' => [
                new RequestMapping(RequestMethod::GET, "/", "index"),
            ],
        ];
    }

    public function index(Request $request) {
        $this->sendJson([
           "ok" => true, 
        ]);
    }
}