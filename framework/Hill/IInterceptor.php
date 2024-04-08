<?php

namespace Hill;

/**
 * Interceptor interface
 */
interface IInterceptor
{
    /**
     * Intercept the http response, processes it and returns.
     * 
     * @param Module $module The module
     * @param Request $request Http request
     * @param Response $response Http response
     * 
     * @return Response
     */
    public function __invoke(Module $module, Request $request, Response $response): Response;
}
