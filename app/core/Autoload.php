<?php
declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $prefix = 'app\\';
    $baseDir = __DIR__ . '/../';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace(['\\', '..'], ['/', ''], $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        error_log("Class not found: {$class}");
    }
});
