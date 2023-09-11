<?php

namespace Hill;

//
// Результат, который хранит ответ и при необходимости будет выброшен как
// исключение.
// Привет, Play! framework :-)
//
class Result extends \Exception
{
    /**
     * 
     */
    private $response;

    /**
     * 
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse() {
        return $this->response;
    }
}
