<?php

namespace DatabaseModule;

use DatabaseModule\Service\ConfigService;
use DatabaseModule\Service\DatabaseService;

class DatabaseModule implements \Hill\IModule
{
    public static function create(array $options = [])
    {
        return [
            'moduleClass'   => DatabaseModule::class,
            'providers'     => [
                [
                    'providerClass' => \DatabaseModule\Service\ConfigService::class,
                    'factory' => [
                        function ($options) {
                            return new ConfigService($options);
                        },
                        [
                            $options
                        ]
                    ]
                ],

                \DatabaseModule\Service\DatabaseService::class,
            ],
            'exportProviders'   => [
                \DatabaseModule\Service\ConfigService::class,
                \DatabaseModule\Service\DatabaseService::class,
            ],
        ];
    }
}
