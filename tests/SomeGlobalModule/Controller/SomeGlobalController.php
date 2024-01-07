<?php

namespace SomeGlobalModule\Controller;

use Hill\JsonResponse;
use Hill\Request;
use Hill\RequestMapping;
use Hill\RequestMethod;

class SomeGlobalController extends \Hill\Controller implements \Hill\IController {
    public static function getConfig() {
        return [
            'path' => '/someGlobalModule',
            'mapping' => [
                new RequestMapping("GET|POST|PUT", "/", "index"),
            ],
        ];
    }

    public function index(Request $request) {
        

        return [
           "ok" => true,
           //"path" => $request->getUrl(PHP_URL_PATH),
        ];
    }
}