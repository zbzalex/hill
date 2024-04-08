<?php

namespace Hill;

/**
 * On module init interface
 */
interface IOnModuleInit
{
    /**
     * Handle module init
     * 
     * @param Module $module The module
     */
    public static function onInit(Module $module): void;
}
