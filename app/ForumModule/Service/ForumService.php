<?php

namespace ForumModule\Service;

class ForumService implements \Hill\IInjectable
{
    public function __construct(
        \DatabaseModule\Service\ConfigService $configService
    ) {
        
    }

    public function sayHello()
    {
    }
}
