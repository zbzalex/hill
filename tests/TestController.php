<?php

namespace Tests;

use Neon\Events;
use Neon\FilterResponseEvent;
use Neon\GetResponseEvent;
use Neon\IController;
use Neon\LifecycleEvents;
use Neon\OnRequestEvent;
use Neon\OnResponseEvent;
use Neon\RequestEvent;
use Neon\RequestMapping;
use Neon\ResponseEvent;

class TestController implements IController
{
  public static function getConfig(): array
  {
    return [
      'path' => '/',
      'mapping' => [
        new RequestMapping('GET', '/', '_index', [
          LifecycleEvents::REQUEST => [
            function(GetResponseEvent $event) {
              echo "received GetResponseEvent from route\n";
            }
          ],
        ]),
      ],
      'subscribedEvents' => [
        LifecycleEvents::REQUEST => [
          function (GetResponseEvent $event) {

            echo "received GetResponseEvent from controller\n";
            // $event->stopPropagation();
            // $event->setResponse(new Response("hello from controller listener"));
          },
        ],
        LifecycleEvents::RESPONSE => [
          function (FilterResponseEvent $event) {
            echo "received FilterResponseEvent from controller\n";
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
