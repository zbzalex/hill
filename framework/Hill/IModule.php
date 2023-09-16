<?php

namespace Hill;

//
// Определяет модуль
//
interface IModule
{
    /**
     * @param array $options
     * 
     * @return array
     */
    public static function create(array $options = []);
}
