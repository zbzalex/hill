<?php

namespace Hill;

/**
 * Middleware interface
 */
interface IMiddleware
{
  /**
   * Handle http request before client and returns http response or nothing.
   * 
   * @param Module $module The module
   * @param Request $request The http request
   * 
   * @return Response|null
   */
  public function __invoke(Module $module, Request $request);
}
