<?php

namespace Hill;

/**
 * Interceptor interface
 */
interface IInterceptor {
    public function __invoke(Request $request, Response $response);
}