<?php

namespace Hill;

//
//
//
class JsonResponse extends Response
{
    public function __construct(array $data)
    {
        parent::__construct(json_encode($data), [
            'content-type' => 'application/json'
        ]);
    }
}
