<?php

declare(strict_types=1);

use Composer\Autoload\ClassLoader;

function includeIfExists($file): ?ClassLoader
{
    if (file_exists($file)) {
        return @include $file;
    }
}
