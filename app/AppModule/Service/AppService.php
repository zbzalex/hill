<?php

namespace AppModule\Service;

use DatabaseModule\Service\DatabaseService;

//
// 
//
class AppService implements \Hill\IInjectable
{
    /**
     * @var ConfigService $configService
     */
    private $configService;
    
    public function __construct(
        ConfigService $configService,
        DatabaseService $databaseService
    ) {
        $this->configService = $configService;
    }
    
    public function sayHello()
    {
        return "hello!";
    }
}
