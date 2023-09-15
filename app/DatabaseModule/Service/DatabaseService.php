<?php

namespace DatabaseModule\Service;

use DatabaseModule\ConnectionManager;

//
//
//
class DatabaseService implements \Hill\IInjectable
{
    private $manager;

    public function __construct(
        ConfigService $configService
    )
    {
        $this->manager = new ConnectionManager();
    }

    public function getManager() {
        return $this->manager;
    }

    private function createConnection(array $config) {

    }
}
