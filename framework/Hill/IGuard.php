<?php

namespace Hill;

//
//
//
interface IGuard
{
    /**
     * @param Request $request
     * 
     * @return bool
     */
    public function __invoke(Request $request);
}
