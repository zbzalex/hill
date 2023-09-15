<?php

namespace Hill;

//
// Определяет модуль
//
interface IModule
{
    public static function create(array $options = []);
}
