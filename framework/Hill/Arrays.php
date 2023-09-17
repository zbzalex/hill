<?php

namespace Hill;

//
//
//
class Arrays
{
    public static function find(array $array, $fn)
    {
        $result = array_filter($array, $fn, ARRAY_FILTER_USE_BOTH);

        return count($result) > 0 ? $result[0] : null;
    }
}
