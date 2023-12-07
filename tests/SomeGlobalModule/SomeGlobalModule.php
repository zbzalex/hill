<?php

namespace SomeGlobalModule;

class SomeGlobalModule implements \Hill\IModule {
    public static function create(array $options = []) {
        return [
            'moduleClass' => SomeGlobalModule::class,
            'global'      => isset($options['global']) ? $options['global'] : false,
            'providers'   => [
                \SomeGlobalModule\Service\SomeGlobalService::class,
            ],
            'exportProviders' => [
                \SomeGlobalModule\Service\SomeGlobalService::class,
            ],
            'importModules' => [
                \SomeVendorModule\SomeVendorModule::class,
            ],
        ];
    }
}