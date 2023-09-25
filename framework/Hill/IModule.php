<?php

namespace Hill;

/**
 * Module class
 */
interface IModule
{
    /**
     * @param array $options
     * 
     * @return array
     */
    public static function create(array $options = []);
}
