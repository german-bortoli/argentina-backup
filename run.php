<?php

$composer = require __DIR__.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Argentina\Helper\Env;

/**
 * Load environment
 */
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

/**
 * Read Configuration
 */
$user = Env::get('MYSQL_USER');
$pass = Env::get('MYSQL_PASSWORD');
$host = Env::get('MYSQL_HOST', 'localhost');


/**
 * Init Database Handler
 */

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $host,
    'username'  => $user,
    'password'  => $pass,
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
]);

$capsule->setAsGlobal();

/**
 * Init console
 */
$app = new Symfony\Component\Console\Application('Argentina Backup', '1.0.0');
$app->add(new Argentina\Console\RunCommand);
$app->run();