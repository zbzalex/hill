<?php

namespace SomeModule\Service;

class SomeService implements \Hill\IInjectable
{
    private $someGlobalService_;

    public function __construct(
        \SomeGlobalModule\Service\SomeGlobalService $someGlobalService
    )
    {
        $this->someGlobalService_ = $someGlobalService;
    }
    
    /**
     * @returns string
     */
    public function sayHello(): string
    {
        return $this->someGlobalService_->sayHello();
    }
}
