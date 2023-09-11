<?php

namespace ForumModule\Controller;

use Hill\Controller;
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
            new RequestMapping(RequestMethod::GET, '/', 'home', [], []),
        ];
    }

    public function home()
    {
        $this->sendText('forum say ' . $this->forumService->sayHello());
    }
}
