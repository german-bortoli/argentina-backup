<?php
if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require __DIR__.'/vendor/autoload.php';
} else {
    require __DIR__.'/../../autoload.php';
}

/**
 * Load enviroment
 */
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$app = new Symfony\Component\Console\Application('Argentina Backup', '1.0.0');
$app->add(new Argentina\Console\RunCommand);
$app->run();