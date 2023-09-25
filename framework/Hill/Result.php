<?php

namespace Hill;

/**
 * Result exception class
 */
class Result extends \Exception
{
    /**
     * @var Response $response The response
     */
    private $response;

    /**
     * @param Response $response The response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return The response
     */
    public function getResponse() {
        return $this->response;
    }
}
