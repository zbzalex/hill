<?php

namespace DatabaseModule;

class DatabaseModule implements \Hill\IModule
{
    public static function controllers()
    {
        return [];
    }

    public static function providers()
    {
        return [
            \DatabaseModule\Service\DataSourceService::class,
        ];
    }

    public static function importModules()
    {
        return [];
    }

    public static function exportProviders()
    {
        return [
            \DatabaseModule\Service\DataSourceService::class,
        ];
    }

    public static function create(array $options) {

        return self::class;
    }
}
