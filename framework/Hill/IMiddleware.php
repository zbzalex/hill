<?php

namespace Hill;

interface IMiddleware {
    public function __invoke(Module $module, Request $request);
}