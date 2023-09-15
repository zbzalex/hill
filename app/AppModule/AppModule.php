<?php

namespace AppModule;

use AppModule\Service\ConfigService;

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
                \InstallModule\InstallModule::class,
                \ForumModule\ForumModule::class,
            ],
            'providers' => [
                [
                    'providerClass' => \AppModule\Service\ConfigService::class,
                    'factory' => [
                        function($options) {
                            return new ConfigService($options);
                        },
                        [
                            $options
                        ]
                    ]
                ],
                \AppModule\Service\AppService::class,
            ],
            'exportProviders' => [
                \AppModule\Service\ConfigService::class,
                \AppModule\Service\AppService::class,
            ]
        ];
    }
}
