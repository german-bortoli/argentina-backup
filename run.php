<?php

$composer = require __DIR__.'/vendor/autoload.php';

/**
 * Load environment
 */
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

/**
 * Init console
 */
$app = new Symfony\Component\Console\Application('Argentina Backup', '1.0.0');
$app->add(new Argentina\Console\RunCommand);
$app->run();