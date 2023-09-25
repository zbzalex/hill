<?php

namespace Hill;

/**
 * Interceptor interface
 * Part of framework architecture
 */
interface IInterceptor {
    public function __invoke(Request $request, Response $response);
}