<?php

namespace SomeGlobalModule\Service;

class SomeGlobalService implements \Hill\IInjectable
{
    private $someVendorService_;

    public $val = null;
    
    public function __construct(
        \SomeVendorModule\Service\SomeVendorService $someVendorService
    )
    {
        $this->someVendorService_ = $someVendorService;
    }
    
    /**
     * @returns string
     */
    public function sayHello(): string
    {
        return $this->someVendorService_->sayHello();
    }
}
