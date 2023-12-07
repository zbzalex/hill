<?php

namespace SomeVendorModule\Service;

class SomeVendorService implements \Hill\IInjectable
{
    public function __construct()
    {
    }
    
    /**
     * @returns string
     */
    public function sayHello(): string
    {
        return "hello from vendor module service\n";
    }
}
