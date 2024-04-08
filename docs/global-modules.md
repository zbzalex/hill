Global modules
==============

```php
// src/GlobalModule.php
<?php

class GlobalModule implements \Hill\IModule {
    public static function create(array $options = []): array {
        return [
            'global' => true,
            'providers' => [
                GlobalService::class,
            ],
            'exportProviders' => [
                GlobalService::class,
            ],
        ];
    }
}
```

```php
// src/GlobalService.php
<?php

class GlobalService implements \Hill\IInjectable {
    /**
     * Constructor
     */
    public function __construct() {

    }

    public function getSomething() {
        return "Hello from global service!";
    }
}
```

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
            // add global module in your root module for use everywhere
            'imports' => [
                GlobalModule::class,
            ],
        ];
    }
}

```
