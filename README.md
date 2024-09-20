Neon Framework
===================

The Neon is a toy set up over Symfony framework for comfortable creating your ideas.

Getting Started
---------------
```
$ php composer.phar require zbzalex/neon
```

```php
// src/Module/App/AppModule.php
use Neon\IModule;

class AppModule implements IModule {
  public static function create(array $options = []): array {
    return [
      'moduleClass' => __CLASS__,
      'importModules' => [
        // SomeModule::class,
        // or custom module custom creation
        // SomeModule::create([
        // 
        //]),
      ],
      'controllers' => [
        AppController::class,
      ],
      'providers' => [
        // Autowired
        AutowiredService::class,
        // Not Autowired
        [
          'providerClass' => NotAutowiredService::class,
          'provider' => [
            function(SomeDependency $someDep, array $args) {
              return new NotAutowiredService($someDep, $args[0]);
            },
            // captured arguments
            [
              $options,
            ],
          ],
          'inject' => [
            SomeDependency::class,
          ],
        ]
      ],
    ];
  }
}
```

```php
// src/Module/App/AutowiredService.php

class AutowiredService {
  public function __construct(
    // SomeServiceForInject $service1
  ) {

  }
}
```

```php
// src/Module/App/AppController.php

use Neon\IController;
use Neon\GetResponseEvent;
use Neon\FilterResponseEvent;
use Neon\RequestMapping;
use Neon\RequestMethod;
use Neon\LifecycleEvents;
use Symfony\Component\HttpFoundation\Request;

class AppController implements IController {
  public static function getConfig(): array {
    return [
      'path' => '/',
      'mapping' => [
        new RequestMapping(RequestMethod::GET, '/', '_index', [
          LifecycleEvents::REQUEST => [
            function (GetResponseEvent $event) {
              // $injector = $event->getInjector();
              // $req = $event->getRequest();
            }
          ],
        ]),
      ],
      'subscribedEvents' => [
          LifecycleEvents::REQUEST => [
            function (GetResponseEvent $event) {
              // TODO
            }
          ],
          LifecycleEvents::RESPONSE => [
            function (FilterResponseEvent $event) {
              // TODO
              // $response = $event->getResponse();
            }
          ],
      ],
    ];
  }

  public function _index(Request $req) {
    return "hello";
  }
}
```

```php
// index.php

$app = new WebApplicationFactory(AppModule::class, [
  'basePath' => '/',
]);

$app->run();
```

Requirements
------------

PHP >= 7.4
PDO

License
-------

Neon is licensed under the ISC license.