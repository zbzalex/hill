<?php

namespace TestModule\Service;

//
//
//
class TestService implements \Hill\IInjectable {
    public function __construct(
    ) {
    }

    public function sayHello() {
        echo "hello\n";
    }
}