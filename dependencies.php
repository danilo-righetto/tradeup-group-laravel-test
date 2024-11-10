<?php

$appDir = "/var/www/app";

$envContent = file($appDir . '/.env');

if (count($envContent) == 0) {
    copy($appDir . '/.env.example', $appDir . '/.env');
    shell_exec('php artisan key:generate');
}