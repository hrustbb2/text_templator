<?php

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\TextUI\TestRunner;

$phpunit = new TestRunner();

try {
    $suit = $phpunit->getTest(__DIR__ . '/TreeTest.php');
    $test_results = $phpunit->run($suit, ['extensions' => []]);
} catch (\Throwable $e) {
    print $e->getMessage() . "\n";
    die ("Unit tests failed.");
}