<?php

namespace Hill;

/**
 * Properties config generator
 */
class PropertiesConfigGenerator {
    /**
     * @var PropertiesConfig $config
     */
    private $config;

    /**
     * @param PropertiesConfig $config
     */
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