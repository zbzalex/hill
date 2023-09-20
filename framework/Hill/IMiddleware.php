<?php

namespace Hill;

/**
 * Middleware interface
 */
interface IMiddleware {
    public function __invoke(Request $request);
}