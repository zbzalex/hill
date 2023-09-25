<?php

namespace Hill;

/**
 * Class for module testing
 */
class Test
{
    public static function createTestModule($moduleConfigOrClass)
    {
        $compiler = new Compiler($moduleConfigOrClass);
        
        return $compiler->compile();
    }
}
