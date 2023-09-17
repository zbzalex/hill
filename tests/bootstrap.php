<?php

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->add(null, __DIR__.'/application');

return $loader;