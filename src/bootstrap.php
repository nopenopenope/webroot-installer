<?php

declare(strict_types=1);

include __DIR__ . '/functions.php';

if ((!$loader = includeIfExists(__DIR__ . '/../vendor/autoload.php'))
    && (!$loader = includeIfExists(__DIR__ . '/../../../autoload.php'))) {
    die('You must set up the project dependencies, run the following commands:' . PHP_EOL .
        'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL);
}

return $loader;
