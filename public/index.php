<?php

$rootDir = dirname(__DIR__);

/** @var \Hill\ClassLoader $classLoader */
$classLoader = require $rootDir . "/framework/include.php";
$classLoader->addFallbackDir($rootDir . "/app/");
$classLoader->addFallbackDir($rootDir . "/system/");

// create and run application
$app = \Hill\ApplicationFactory::create(\AppModule\AppModule::create([
    'rootDir' => $rootDir
]), "/");

$app->setErrorHandler(function(\Hill\HttpException $e) {
    $response = new \Hill\Response(null);

    $response->write("<!doctype html>");
    $response->write("<html>");
    $response->write("<head>");
    $response->write(sprintf("<title>%d</title>", $e->getCode()));
    $response->write("</head><body>");
    $response->write($e->getMessage());
    $response->write("</body>");
    $response->write("</html>");

    return $response;
});
$app->run();
