<?php

namespace TestModule\Service;

//
//
//
class TestService implements \Hill\IInjectable {
    public function __construct() {
    	// some contructed here
    }

    public function sayHello() {
	    echo "hello\n";
    }
}
