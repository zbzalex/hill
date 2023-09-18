<?php

namespace Hill;

//
//
//
class PropertiesConfigGenerator {
    private $config;

    public function __construct(PropertiesConfig $config) {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function generate() {
        $lines = [];
        foreach($this->config->toArray() as $key => $value) {
            $lines[] = sprintf(
                '%s = %s',
                $key,
                $value
            );
        }

        return implode("\n", $lines);
    }
}