<?php

namespace TestModule;

//
//
//
class TestModule implements \Hill\IModule {
    public static function create(array $options = []) {
        echo "create test module\n";
        return [
            'moduleClass' => TestModule::class,
            'controllers' => [
                \TestModule\Controller\TestController::class,
            ],
            'providers' => [
                //\TestModule\Service\SomeService::class,
                \TestModule\Service\TestService::class,
            ],
            'importModules' => [
                //\SomeModule\SomeModule::class,
            ],
        ];
    }
}