<?php

namespace Module;

use Hill\Request;
use Hill\RequestMapping;

class UsersController extends \Hill\Controller implements \Hill\IController
{
    public static function getConfig(): array
    {
        return [
            'path' => '/users',
            'mapping' => [
                new RequestMapping("GET", "/", "_index")
            ],
        ];
    }
    
    public function _index(Request $req)
    {
        return "hello";
    }
}
