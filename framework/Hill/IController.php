<?php

namespace Hill;

//
// Определяет контроллер
//
interface IController {
    /**
     * @return string
     */
    public static function path();

    /**
     * @return RequestMapping[]
     */
    public static function routes();
}
