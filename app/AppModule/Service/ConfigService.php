<?php

namespace AppModule\Service;

//
//
//
class ConfigService
{
    private $options;
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }
    
    public function getProperty($key, $default = null)
    {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }
}
