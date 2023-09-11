<?php

namespace Hill;

//
//
//
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
}
