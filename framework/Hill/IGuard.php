<?php

namespace Hill;

/**
 * Guard interface
 * Part of framework architecture
 */
interface IGuard
{
    /**
     * @param Request $request
     * 
     * @return bool
     */
    public function __invoke(Request $request);
}
