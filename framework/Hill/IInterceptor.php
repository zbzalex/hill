<?php

namespace Hill;

interface IInterceptor {
    public function __invoke(Module $module, Request $request, Response $response);
}