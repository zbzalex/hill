<?php

namespace InstallModule;

//
//
//
class InstallModule implements \Hill\IModule {
    public static function create(array $options = []) {
        return [
            'moduleClass' => InstallModule::class,
            'controllers' => [
                \InstallModule\Controller\InstallController::class,
            ],
        ];
    }
}