<?php

namespace Hill;

/**
 * Middleware interface
 * Part of framework architecture
 */
interface IMiddleware {
    public function __invoke(Request $request);
}