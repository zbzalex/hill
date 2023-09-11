<?php

require __DIR__."/Hill/ClassLoader.php";

// Создаём загрузчик классов
$classLoader = new \Hill\ClassLoader();

// Добавляем префикс для имени пространства фреймворка
$classLoader->addFallbackDir(__DIR__ . "/");

// Регистрируем загрузчик
$classLoader->register();

return $classLoader;