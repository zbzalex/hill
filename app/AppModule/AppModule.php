<?php

namespace AppModule;

//
//
//
class AppModule implements
    \Hill\IModule
{
    public function __construct()
    {
    }
    
    // @@@ \Hill\IModule
    /**
     * @return string[]
     */
    public static function controllers()
    {
        return [
            \AppModule\Controller\AppController::class,
        ];
    }

    // @@@ \Hill\IModule
    /**
     * @return string[]
     */
    public static function providers()
    {
        return [
            \AppModule\Service\ConfigService::class,
            \AppModule\Service\AppService::class,
        ];
    }

    // @@@ \Hill\IModule
    /**
     * @return string[]
     */
    public static function importModules()
    {
        return [
            \DatabaseModule\DatabaseModule::create([
                'type' => 'mysql',
                'host' => 'localhost',
                'username' => 'root',
                'password' => '123',
                'database' => 'johncms'
            ]),
            \ForumModule\ForumModule::class,
        ];
    }

    public static function exportProviders()
    {
        return [
            \AppModule\Service\AppService::class,
        ];
    }
}
