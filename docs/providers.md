Providers
=========

#### Что такое провайдер?

Провайдер - это сервис, который будет инстанцирован инжектором, учитывая все зависимости, которые необходимы.
Провайдер может быть управляемым или не управляемым. Управляемый сервис не может быть создан пользователем, он инстанцируется инжектором и должен наследовать интерфейс ```\Hill\IInjectable```.

Пример управляемого провайдера:
```php
<?php
// app/MyModule\Service\MyInjectableService.php

namespace MyModule\Service;

class MyInjectableService implements \Hill\IInjectable {
    public function __construct(
        \SomeModule\Service\SomeService $someService
    ) {
    }
}
```

Теперь мы можем использовать сервис SomeService из модуля SomeModule, при условии, что в нашем модуле мы его импортируем и модуль SomeModule экспортирует провайдер SomeService.


#### Как создать фабрику?

Для начала создадим сервис, который НЕ НУЖНО помечать как управляемый т.к мы его создаем с помощью нашей фабрики.

```php
<?php
// app/MyModule/Service/MyService.php

namespace MyModule\Service;

class MyService {
    public function __construct($a, $b, $c) {

    }
}
```

В модуле нужно указать что провайдер это наша фабрика.

```php
<?php
// app/MyModule/MyModule.php

namespace MyModule;

class MyModule implements \Hill\IModule {
    public static function create(array $options = []) {
        return [
            'moduleClass' => MyModule::class,
            'providers' => [
                [
                    'providerClass' => \MyModule\Service\MyProvider::class,
                    'factory' => [
                        function($a, $b, $c) {
                            return new MyProvider($a, $b, $c);
                        },
                        [
                            1,
                            2,
                            3
                        ]
                    ]
                ]
            ]
        ];
    }
}
```
