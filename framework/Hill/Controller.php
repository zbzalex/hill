<?php

namespace Hill;

/**
 * Base abstract controller class
 */
abstract class Controller
{
    /**
     * @throws Result
     */
    public function send(Response $response)
    {
        throw new Result($response);
    }

    /**
     * @throws Result
     */
    public function sendJson(array $data)
    {
        $this->send(new JsonResponse($data));
    }

    /**
     * @throws Result
     */
    public function sendText($text)
    {
        $this->send(new Response($text));
    }
    
    /**
     * @throws HttpException
     */
    public function httpException($message = "", $code = 0)
    {
        throw new HttpException($message, $code);
    }
}
