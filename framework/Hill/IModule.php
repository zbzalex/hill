<?php

namespace Hill;

//
// Определяет модуль
//
interface IModule
{
    /**
     * @return string[]
     */
    public static function controllers();

    /**
     * @return string[]
     */
    public static function providers();

    /**
     * @return string[]
     */
    public static function importModules();

    /**
     * @return string[]
     */
    public static function exportProviders();
}
