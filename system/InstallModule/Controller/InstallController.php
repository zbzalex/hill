<?php

namespace InstallModule\Controller;

use Hill\RequestMapping;
use Hill\RequestMethod;

class InstallController extends \Hill\Controller implements \Hill\IController
{
    public function __construct()
    {
    }
    
    public static function path()
    {
        return "/";
    }

    public static function routes()
    {
        return [
            new RequestMapping(RequestMethod::GET, "/install", "install"),
        ];
    }

    public function install()
    {
        $this->sendText("install");
    }
}
