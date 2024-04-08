<?php

namespace Hill;

/**
 * Redirect response class
 */
class RedirectResponse extends Response {

    /**
     * Contructor
     * 
     * @param string $to Redirect url
     */
    public function __construct($to) {
        parent::__construct(null, [
            'Location' => $to
        ]);
    }
}