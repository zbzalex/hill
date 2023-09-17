<?php

namespace TestModule\Service;

//
//
//
class SomeService implements \Hill\IInjectable
{
    public function __construct()
    {
    }
    
    public function saySome()
    {
        echo "some!!!\n";
    }
}
