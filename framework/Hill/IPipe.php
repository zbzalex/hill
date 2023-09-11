<?php

namespace Hill;

//
//
//
interface IPipe {
    /**
     * @return mixed
     */
    public function __invoke(Request $request);
}