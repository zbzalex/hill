<?php

namespace Module;

class UsersModule implements \Hill\IModule
{
    public static function create(array $options = []): array
    {
        return [
            'moduleClass'   => __CLASS__,
            'controllers'   => [
                \Module\UsersController::class
            ],
        ];
    }
}
