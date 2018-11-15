<?php
// .env
use Dotenv\Dotenv;

if (file_exists(realpath(__DIR__ . '/.env'))) {
    $file = '.env';
} else {
    $file = '.env.default';
}

$env = new Dotenv(realpath(__DIR__), $file);
$env->load();