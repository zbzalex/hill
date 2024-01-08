<?php

namespace Hill;

class TypedArray
{
    private array $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function has(string $key)
    {
        return isset($this->array[$key]);
    }

    public function get(string $key, $default = null)
    {
        return isset($this->array[$key]) ? $this->array[$key] : $default;
    }

    public function getString(string $key, $default = null)
    {
        $value = $this->get($key, $default);

        return is_scalar($value) ? $value : $default;
    }

    public function getInt(string $key, $default = 0)
    {
        $value = $this->get($key, $default);

        return preg_match("/^\d+$/", $value)
            ? $value
            : $default;
    }
    
    public function getFloat(string $key, $default = 0.0)
    {
        $value = $this->get($key, $default);

        return preg_match("/^\d+(\.\d+)?$/", $value)
            ? $value
            : $default;
    }

    public function getBool(string $key, $default = false)
    {
        $value = $this->get($key, $default);

        return is_bool($value) ? $value : $default;
    }

    public function getArray(string $key)
    {
        $value = $this->get($key, null);

        return $value !== null && is_array($value) ? new TypedArray($value) : null;
    }
}
