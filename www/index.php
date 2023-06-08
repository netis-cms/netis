<?php

declare(strict_types=1);

use App\Bootstrap;

// Composer autoload.
require __DIR__ . '/../vendor/autoload.php';

// Run application.
$boot = Bootstrap::boot();
$boot->app()->run();
