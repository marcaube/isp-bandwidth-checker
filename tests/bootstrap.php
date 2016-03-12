<?php

if (!file_exists($autoloadFile = __DIR__ . '/../vendor/autoload.php') &&
    !file_exists($autoloadFile = __DIR__ . '/../../../vendor/autoload.php')) {
    die('You must set up the project dependencies, run composer install' . PHP_EOL);
}

require $autoloadFile;
