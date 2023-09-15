<?php

namespace AppModule;

//
//
//
class AppModule implements
    \Hill\IModule
{
    public static function create(array $options = [])
    {
        return [
            'moduleClass' => AppModule::class,
            'controllers' => [
                \AppModule\Controller\AppController::class,
            ],
            'importModules'    => [
                \DatabaseModule\DatabaseModule::create([
                    'type' => 'mysql',
                    'host' => 'localhost',
                    'username' => 'root',
                    'password' => '123',
                    'database' => 'johncms'
                ]),
                \ForumModule\ForumModule::class,
            ],
            'providers' => [
                \AppModule\Service\ConfigService::class,
                \AppModule\Service\AppService::class,
            ],
            'exportProviders' => [
                \AppModule\Service\ConfigService::class,
                \AppModule\Service\AppService::class,
            ]
        ];
    }
}
