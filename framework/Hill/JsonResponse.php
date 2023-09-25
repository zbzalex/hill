<?php

namespace Hill;

/**
 * Json response class
 */
class JsonResponse extends Response
{
    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct(json_encode($data), [
            'content-type' => 'application/json'
        ]);
    }
}
