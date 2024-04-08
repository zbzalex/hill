<?php

namespace Hill;

/**
 * Class for module testing
 */
class Test
{
    /**
     * Creates container from input module
     * 
     * @param array|string $moduleConfigOrClass
     * 
     * @return Container
     */
    public static function createTestModule($moduleConfigOrClass): Container
    {
        $compiler = new Compiler($moduleConfigOrClass);

        return $compiler->compile();
    }
}
