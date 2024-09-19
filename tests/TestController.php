<?php

namespace Tests;

use Neon\Events;
use Neon\IController;
use Neon\OnRequestEvent;
use Neon\OnResponseEvent;
use Neon\RequestEvent;
use Neon\RequestMapping;
use Neon\ResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class TestController implements IController
{
  public static function getConfig(): array
  {
    return [
      'path' => '/',
      'mapping' => [
        new RequestMapping('GET', '/', '_index', [
          Events::REQUEST => [
            function() {
              echo "call request.event from route\n";
            }
          ],
        ]),
      ],
      'events' => [
        Events::REQUEST => [
          function (OnRequestEvent $event) {
            echo "call request.event from controller";
            $event->stopPropagation();
            $event->setResponse(new Response("hello from controller listener"));
          },
        ],
        Events::RESPONSE => [
          function (OnResponseEvent $event) {
            echo "call response.event from controller";
          }
        ],
      ],
    ];
  }

  public function _index()
  {
    return "hello";
  }
}
