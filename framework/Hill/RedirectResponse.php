<?php

namespace Hill;

//
//
//
class RedirectResponse extends Response {
    public function __construct($to) {
        parent::__construct(null, [
            'Location' => $to
        ]);
    }
}