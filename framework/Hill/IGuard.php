<?php

namespace Hill;

//
//
//
interface IGuard
{
    /**
     * @return bool
     */
    public function __invoke(Request $request);
}
