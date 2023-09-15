<?php

namespace ForumModule;

use DatabaseModule\DatabaseModule;

class ForumModule implements \Hill\IModule
{
    public static function create(array $options = [])
    {
        return [
            'moduleClass' => ForumModule::class,
            'controllers' => [
                \ForumModule\Controller\ForumController::class,
            ],
            'providers' => [
                \ForumModule\Service\ForumService::class,
            ],
            'importModules' => [
                \DatabaseModule\DatabaseModule::class,
            ]
        ];
    }
}
