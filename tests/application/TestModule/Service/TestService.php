<?php

namespace TestModule\Service;

//
//
//
class TestService implements \Hill\IInjectable {
    private $someService;

    public function __construct(
        \TestModule\Service\SomeService $someService
    ) {
        $this->someService = $someService;    
    }

    public function sayHello() {
        echo "hello\n";
        
        $this->someService->saySome();
    }
}