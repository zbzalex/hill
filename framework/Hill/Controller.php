<?php

namespace Hill;

/**
 * Base abstract controller class
 */
abstract class Controller
{    
    /**
     * @throws HttpException
     */
    public function httpException($message = "", $code = 0)
    {
        throw new HttpException($message, $code);
    }
}
