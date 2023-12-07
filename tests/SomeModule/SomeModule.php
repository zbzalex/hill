<?php

namespace SomeModule;

class SomeModule implements \Hill\IModule {
    public static function create(array $options = []) {
        return [
            'moduleClass' => SomeModule::class,
            'providers'   => [
                \SomeModule\Service\SomeService::class,
            ],
            'exportProviders' => [
                \SomeModule\Service\SomeService::class,
            ]
        ];
    }
}