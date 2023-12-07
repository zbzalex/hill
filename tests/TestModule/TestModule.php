<?php

namespace TestModule;

//
//
//
class TestModule implements \Hill\IModule {
    public static function create(array $options = []) {
        return [
            'moduleClass' => TestModule::class,
            'controllers' => [
                \TestModule\Controller\TestController::class,
            ],
            'providers' => [
                \TestModule\Service\TestService::class,
            ],
            'importModules' => [
                \SomeGlobalModule\SomeGlobalModule::create([
                    'global' => true,
                ]),
                \SomeModule\SomeModule::class,
            ],
        ];
    }
}