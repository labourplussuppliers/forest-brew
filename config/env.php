<?php

/**
 * ----------------------------------------------------------
 * Load .env values into getenv() and $_ENV
 * ----------------------------------------------------------
 */

function loadEnv(string $path): void
{
    if (!file_exists($path) || !is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (trim($line) === '' || str_starts_with(trim($line), '#')) {
            continue;
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2) + [1 => '']);

        if ($key === '') {
            continue;
        }

        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
        }

        if (getenv($key) === false) {
            putenv("{$key}={$value}");
        }
    }
}

loadEnv(__DIR__ . '/../.env');
