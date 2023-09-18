<?php

namespace Hill;

//
//
//
class PropertiesConfig
{
    private $file;
    private $properties;
    
    public function __construct($file)
    {
        $this->file = $file;
        $this->properties = [];
    }

    public function load()
    {
        if (!file_exists($this->file))
            throw new \Exception($this->file);

        $lines = file($this->file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (trim($line) === '' || strpos(ltrim($line), '#') === 0) {
                continue;
            }

            $line = explode('=', $line, 2);
            if (count($line) != 2)
                continue;

            $line = array_map('trim', $line);

            $this->properties[$line[0]] = $line[1];
        }
    }

    public function addProperties(array $config)
    {
        $this->properties = array_merge($this->properties, $config);
    }

    public function get($key, $default = null)
    {
        return isset($this->properties[$key]) ? $this->properties[$key] : $default;
    }

    public function getKeys()
    {
        return array_keys($this->properties);
    }

    public function containsKey($key)
    {
        return $this->get($key) !== null;
    }

    public function getArray($key)
    {
        if (!isset($this->properties[$key]))
            throw new \Exception('Undefined property');

        return array_map('trim', explode(',', $this->properties[$key]));
    }

    public function getBool($key)
    {
        $value = $key;
        return $value !== false
            && $value !== ''
            && $value !== '0'
            && $value !== 'off'
            && $value !== 0;
    }

    public function toArray()
    {
        return $this->properties;
    }
}
