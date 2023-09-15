<?php

/** @var \Hill\ClassLoader $classLoader */
$classLoader = require dirname(__DIR__)."/framework/include.php";

$app = \Hill\ApplicationFactory::create($classLoader, dirname(__DIR__), \AppModule\AppModule::class, "/");
$app->run();
