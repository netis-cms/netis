<?php

declare(strict_types=1);

// Composer autoload.
require __DIR__ . '/../vendor/autoload.php';

// Run application.
$boot = App\Bootstrap::boot();
$boot->app()->run();
