Quick start
===========

### Installation
```
$ php composer.phar require zbzalex/hill-php
```

or

```
$ composer require zbzalex/hill-php
```

### Create an application and run

```php
// src/BootstrapModule.php
<?php

class BootstrapModule implements \Hill\IModule {
    public static function create(array $options = []): array {
        return [
            'moduleClass' => __CLASS__,
            'controllers' => [
                BootstrapController::class,
            ],
            'providers' => [
                BootstrapService::class,
            ],
            // optional
            'exportProviders' => [
                BootstrapService::class,
            ],
        ];
    }
}
```

```php
// src/BootstrapController.php
<?php

// Controller not need to have IInjectable implementation for inject provided services
// You can enumerate they in module 'providers' key.
class BootstrapController extends \Hill\Controller implements \Hill\IController {
    public static function getConfig(): array {
        return [
            // Controller path
            'path' => '/',
            // Route mapping
            'mapping' => [
                new \Hill\RequestMapping("GET", "/", "index"),
                new \Hill\RequestMapping("GET", "/listOfUsers", "getListOfUsers")
            ],
            'middlewares' => [
                function(\Hill\Module $thisModule, \Hill\Request $req) {
                    if (rand()%2 == 0) {
                        return new Response("Bad request", 400);
                    }

                    return null; // this handle must be return null or Response object
                },
            ],
            'interceptors' => [
                function(\Hill\Module $thisModule, \Hill\Request $req, \Hill\Response $res) {
                    // Transform your response here
                    $res->clear(); // clear all content
                    $res->write("Hello!"); // and write a something new

                    return $res;
                }
            ],
        ];
    }

    /** @var BootstrapService $bootstrapService Bootstrap service */
    private $bootstrapService;

    /**
     * Constructor
     * 
     * @param BootstrapService $bootstrapService Injected BootstrapService
     */
    public function __construct(
        BootstrapService $bootstrapService
    ) {
        $this->bootstrapService = $bootstrapService;
    }

    public function index(\Hill\Request $req) {
        return "hello!\n";
    }

    public function getListOfUsers(\Hill\Request $req) {
        return $this->bootstrapService->getListOfUsers();
    }
}
```

```php
// src/BootstrapService.php
<?php

class BootstrapService implements \Hill\IInjectable {
    /** @var \PDO $pdo Data source */
    private $pdo;

    /**
     * Constructor
     */
    public function __construct() {
        $this->pdo = new \PDO("postgres:host=localhost;port=5432;dbname=project", "postgres", "1234");
    }

    /**
     * Retrieve list of users with two selected fields: id, username
     * 
     * @return array
     */
    public function getListOfUser() {
        $st = $this->pdo->prepare("SELECT id, username FROM users;");
        // TODO: check on error here
        return $st->fetchAll(\PDO::FETCH_ALL);
    }
}
```

```php
// index.php
<?php

require_once __DIR__ . "/vendor/autoload.php";

// Creates an application via factory. You can push your options array as second argument also.
$app = \Hill\WebApplicationFactory::create(BootstrapModule::class, [
    'option1' => 'value1',
]);

// Run
$app->run();
```
