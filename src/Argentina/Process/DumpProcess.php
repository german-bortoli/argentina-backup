<?php

namespace Argentina\Process;

use Argentina\Helper\Env;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DumpProcess
{

    public static function get(OutputInterface & $output)
    {

        $path = Env::get('BACKUP_DIRECTORY');
        $mysqldump = Env::get('MYSQLDUMP_BIN', '/usr/bin/mysqldump');

        $user = Env::get('MYSQL_USER');
        $pass = Env::get('MYSQL_PASSWORD');
        $host = Env::get('MYSQL_HOST', 'localhost');
        $compression = Env::get('COMPRESS_TYPE', false);

        $databases = Env::get('MYSQL_DATABASES', '*');

        if (!$user) {
            throw new ProcessException("MySQL user should be configured");
        }

        if (!$path) {
            throw new ProcessException("Backup directory should be configured");
        }

        if (!is_dir($path)) {
            throw new ProcessException("Backup directory is wrong or does not exists.");
        }

        $path = rtrim($path, DIRECTORY_SEPARATOR);
        $args = [];

        $file = $path.'/'.date('Y-m-d-His').'.sql';

        array_push($args, $mysqldump);
        array_push($args, "-h{$host}");
        array_push($args, "-u{$user}");

        if ($pass) {
            array_push($args, "-p'{$pass}'");
        }

        if ($databases == '*') {
            array_push($args, '--all-databases');
            $output->writeln('<info>Backup of all databases</info>');
        } else {
            array_push($args, "--databases {$databases}");
            $output->writeln("<info>Backup of databases {$databases}</info>");
        }

        $extra_params = Env::get('MYSQLDUMP_EXTRA_PARAMS');

        array_push($args, $extra_params);


        if ($compression) {
            $cformat = ($compression == 'gzip') ? 'gz' : $compression;
            $output->writeln("<info>Using compression {$cformat}</info>");
            array_push($args, "| {$compression} > {$file}.{$cformat}");
        } else {
            array_push($args, " > {$file}");
        }

        $output->writeln("<info>File created into {$file}</info>");

        // Launch process
        return new Process(implode(' ', $args));
    }
}