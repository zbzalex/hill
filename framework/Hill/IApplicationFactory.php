<?php

namespace Hill;

/**
 * Application factory interface
 */
interface IApplicationFactory
{
    /**
     * Creates a new application
     * 
     * @param string|array $moduleConfigOrClass Module config or module class
     * @param array $options Options
     * 
     * @return IApplication
     */
    public static function create($moduleConfigOrClass, array $options = []): IApplication;
}
