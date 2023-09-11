<?php

namespace ForumModule;

use AppModule\Service\AppService;
use AppModule\Service\ConfigService;

class ForumModule implements \Hill\IModule
{
    // @@@ \Hill\IModule
    /**
     * @return string[]
     */
    public static function controllers()
    {
        return [
            \ForumModule\Controller\ForumController::class
        ];
    }

    // @@@ \Hill\IModule
    /**
     * @return string[]
     */
    public static function providers()
    {
        return [
            \ForumModule\Service\ForumService::class,
        ];
    }

    // @@@ \Hill\IModule
    /**
     * @return string[]
     */
    public static function importModules()
    {
        return [
            \AppModule\AppModule::class,
        ];
    }

    public static function exportProviders()
    {
        return [];
    }
}
