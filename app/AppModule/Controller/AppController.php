<?php

namespace AppModule\Controller;

use AppModule\Guard\AuthGuard;
use AppModule\Pipe\IntParam;
use AppModule\Service\AppService;
use AppModule\Service\RenderService;
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
    private $renderService;

    /**
     * 
     */
    public function __construct(
        AppService $appService,
        RenderService $renderService
    ) {
        $this->appService = $appService;
        $this->renderService = $renderService;
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
            new RequestMapping(RequestMethod::GET, '/profile/:id', 'profile', [
                new IntParam('id'),
            ], [
                AuthGuard::class
            ]),
        ];
    }

    public function home($request)
    {
        $this->sendText(
            $this->renderService->render('document.html.php', [
                'title'     => 'Home',
                'content'   => 'hello'
            ])
        );
    }

    public function profile($request)
    {
        /** @var int id */
        $id = $request->attributes['id'];

        $this->send(new Response(
            "ID = " . $id
        ));
    }
}
