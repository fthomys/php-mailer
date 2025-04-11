<?php

namespace PhpMailer;

function autoloader(string $classPath): void
{
    $baseDir = __DIR__ . '/../lib';

    $parts = explode('\\', $classPath);

    if (isset($parts[0]) && $parts[0] === 'PhpMailer') {
        $parts[0] = $baseDir;
        $filePath = implode(DIRECTORY_SEPARATOR, $parts) . '.php';

        if (file_exists($filePath) && is_readable($filePath)) {
            require($filePath);
        }
    }
}
