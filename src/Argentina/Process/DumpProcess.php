<?php

namespace Argentina\Process;

use Argentina\Helper\Env;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Illuminate\Database\Capsule\Manager as Capsule;

class DumpProcess
{


    public static function get(OutputInterface & $output)
    {
        $backupPath = Env::get('BACKUP_DIRECTORY');
        $mysqldump = Env::get('MYSQLDUMP_BIN', '/usr/bin/mysqldump');

        $user = Env::get('MYSQL_USER');
        $pass = Env::get('MYSQL_PASSWORD');
        $host = Env::get('MYSQL_HOST', 'localhost');

        if (!$user) {
            throw new ProcessException("MySQL user should be configured");
        }
        if (!$backupPath) {
            throw new ProcessException("Backup directory should be configured");
        }
        if (!is_dir($backupPath)) {
            throw new ProcessException("Backup directory is wrong or does not exists.");
        }

        $backupPath = rtrim($backupPath, DIRECTORY_SEPARATOR);

        $extra_params = Env::get('MYSQLDUMP_EXTRA_PARAMS');

        $tmpPath = $backupPath . '/.tmp/';

        //Remove temporary
        $output->writeln("<comment>Clearing tmp</comment>");
        $command = new Process("rm -rf {$tmpPath}");
        $command->run();

        //Create temporal directory
        $command = new Process("mkdir -p {$tmpPath}");
        $command->run();


        $databases = Capsule::select(Capsule::raw('show databases'));

        foreach ($databases as $database) {

            if (!isset($database->Database)) {
                continue;
            }

            $databaseName = $database->Database;

            $output->writeln("<info>*** Working on: {$databaseName} </info>");

            $backupCommand = "{$mysqldump} -h{$host} -u{$user} -p'{$pass}' {$extra_params} {$databaseName} | gzip > {$tmpPath}{$databaseName}.sql.gz";
            $output->writeln("<comment>{$backupCommand} </comment>");


            $command = new Process("{$backupCommand}");
            $command->run();

        }

        $file = $backupPath . '/' . date('Y-m-d-His') . '.tar.gz';

        $output->writeln("<comment>Compressing files...</comment>");
        $command = new Process("tar -zcvf {$file} -C {$tmpPath} .");
        $command->run();

        if ($command->isSuccessful()) {
            $output->writeln("<info>Finished successfully... Backup file {$file}</info>");
        }

        //Clearing tmp
        $command = new Process("rm -rf {$tmpPath}");
        $command->run();
    }
}