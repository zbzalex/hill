<?php

namespace SomeVendorModule;

class SomeVendorModule implements \Hill\IModule {
    public static function create(array $options = []) {
        return [
            'moduleClass' => SomeVendorModule::class,
            'providers'   => [
                \SomeVendorModule\Service\SomeVendorService::class,
            ],
            'exportProviders' => [
                \SomeVendorModule\Service\SomeVendorService::class,
            ],
        ];
    }
}