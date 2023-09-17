<?php

namespace Hill;

//
//
//
class Test
{
    public static function createTestModule($moduleConfigOrClass)
    {
        $compiler = new Compiler($moduleConfigOrClass);
        
        return $compiler->compile();
    }
}
