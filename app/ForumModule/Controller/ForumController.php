<?php

namespace ForumModule\Controller;

use Hill\Controller;
use Hill\Request;
use Hill\RequestMapping;
use Hill\RequestMethod;

class ForumController extends Controller implements \Hill\IController
{
    private $forumService;
    public function __construct(\ForumModule\Service\ForumService $forumService)
    {
        $this->forumService = $forumService;
    }

    public static function path()
    {
        return "/forum";
    }

    public static function routes()
    {
        return [
            new RequestMapping(RequestMethod::GET, '/', 'home', [
                function (Request $req) {
                    $req->query['id'] = isset($req->query['id'])
                        ? (int) $req->query['id']
                        : 0;
                }
            ], []),
        ];
    }

    public function home(Request $req)
    {
        $this->sendText('forum say ' . $this->forumService->sayHello());
    }
}
