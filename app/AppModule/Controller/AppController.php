<?php

namespace AppModule\Controller;

use AppModule\Guard\AuthGuard;
use AppModule\Service\AppService;
use Hill\Controller;
use Hill\RequestMapping;
use Hill\RequestMethod;
use Hill\Response;

//
//
//
class AppController extends Controller implements \Hill\IController
{
    private $appService;

    /**
     * 
     */
    public function __construct(
        AppService $appService
    ) {
        $this->appService = $appService;
    }

    /**
     * 
     */
    public static function path()
    {
        return "/";
    }
    
    /**
     * 
     */
    public static function routes()
    {
        return [
            new RequestMapping(RequestMethod::GET, '/', 'home', [], [
                AuthGuard::class,
            ]),
            new RequestMapping(RequestMethod::GET, '/profile/:id', 'profile', [], [
                AuthGuard::class
            ]),
        ];
    }

    public function home($request)
    {
        $query = $request->query;

        $this->send(new Response(
            $this->appService->sayHello()
        ));
    }

    public function profile($request)
    {
        $id = $request->attributes['id'];

        $this->send(new Response(
            "ID = " . $id
        ));
    }
}
