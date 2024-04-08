<?php

namespace Hill;

/**
 * Controller interface
 */
interface IController {

    /**
     * Returns controller config
     * 
     * @return array
     */
    public static function getConfig(): array;
}
