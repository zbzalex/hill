<?php

namespace Hill;

/**
 * Pipe class
 * Part of framework architecture
 */
interface IPipe {
    /**
     * @return mixed
     */
    public function __invoke(Request $request);
}