<?php

namespace ForumModule\Service;

class ForumService implements \Hill\IInjectable
{
    public function __construct(
        \DatabaseModule\Service\DatabaseService $databaseService
    ) {
        
    }

    public function sayHello()
    {
    }
}
