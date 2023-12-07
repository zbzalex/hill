<?php

namespace TestModule\Service;

class TestService implements \Hill\IInjectable {
    private $someService_;
    
    public function __construct(
        \SomeModule\Service\SomeService $someService
    ) {
        $this->someService_ = $someService;
    }

    public function sayHello() {
        return $this->someService_->sayHello();
    }
}
