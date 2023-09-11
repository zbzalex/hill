<?php

namespace ForumModule\Service;

class ForumService implements \Hill\IInjectable {
    private $appService;
    public function __construct(\AppModule\Service\AppService $appService) {
        $this->appService = $appService;
    }

    public function sayHello() {
        return $this->appService->sayHello();
    }
}