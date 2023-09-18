<?php

namespace Tests;

//
//
//
class PropertiesConfigTest extends \PHPUnit\Framework\TestCase
{
    public function testConfig()
    {
        $config = new \Hill\PropertiesConfig(__DIR__."/app.properties");
        $config->load();

        $this->assertSame($config->get('db.host'), 'localhost', 'Expected localhost');

        $config->addProperties([
            'logger.level' => 'debug, warn, error, fatal',
        ]);

        $generator = new \Hill\PropertiesConfigGenerator($config);
        $output = $generator->generate();

        // 
    }
}
