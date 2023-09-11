<?php

namespace AppModule\Pipe;

use Hill\Request;

class IntParam implements \Hill\IPipe
{
    private $key;
    private $defaultValue;
    public function __construct($key, $defaultValue = 0)
    {
        $this->key = $key;
        $this->defaultValue = $defaultValue;
    }
    
    public function __invoke(Request $request)
    {
        $request->attributes[$this->key] =
            isset($request->attributes[$this->key])
            ? (preg_match('/^[0-9]+$/', $request->attributes[$this->key])
                ? (int) ltrim($request->attributes[$this->key], '0') + 0
                : $this->defaultValue)
            : $this->defaultValue;
    }
}
