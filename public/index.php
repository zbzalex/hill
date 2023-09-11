<?php

/** @var \Hill\ClassLoader $classLoader */
$classLoader = require dirname(__DIR__)."/framework/include.php";

// Чтобы наше приложение заработало, нужно указать из какой папки загружать
// классы нашего исходного кода.
$classLoader->addFallbackDir(dirname(__DIR__)."/app/");

$app = \Hill\ApplicationFactory::create(\AppModule\AppModule::class, "/");
$app->run();
