<?php

namespace Argentina\Process;

use Argentina\Factory\MountManagerFactory;
use Argentina\Helper\Env;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DumpProcess
{

    public static function get(OutputInterface & $output)
    {
        $mysqldump = Env::get('MYSQLDUMP_BIN', '/usr/bin/mysqldump');

        $user = Env::get('MYSQL_USER');
        $pass = Env::get('MYSQL_PASSWORD');
        $host = Env::get('MYSQL_HOST', 'localhost');
        $compression = Env::get('COMPRESS_TYPE', false);

        $databases = Env::get('MYSQL_DATABASES', '*');

        if (!$user) {
            throw new ProcessException("MySQL user should be configured");
        }

        $args = [];

        $filename = date('Y-m-d-His') . '.sql';
        $cformat = ($compression == 'gzip') ? 'gz' : $compression;

        $tmpDirectory = rtrim(Env::getTmpDirectory(), DIRECTORY_SEPARATOR);
        $tmpFile = $tmpDirectory . DIRECTORY_SEPARATOR . $filename;

        $tmpFile = ($compression) ? $tmpFile . '.' . $cformat : $tmpFile;

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
            $output->writeln("<info>Using compression {$cformat}</info>");
            array_push($args, "| {$compression} > {$tmpFile}");
        } else {
            array_push($args, " > {$tmpFile}");
        }

        $output->writeln("<info>Starting the backup process, tmp file: {$tmpFile}</info>");

        // Launch process
        $process = new Process(implode(' ', $args));
        $process->run();

        if ($process->isSuccessful()) {
            self::uploadBackup($output, $tmpFile);

        } else {
            $output->writeln("<error>Oh no, some error happened deleting {$tmpFile}</error>");
            // Remove file
        }

        $process = new Process("rm {$tmpFile}");

        return $process;
    }

    public static function uploadBackup(OutputInterface & $output, $file)
    {
        $filename = pathinfo($file, PATHINFO_BASENAME);

        $storage = Env::get('BACKUP_STORAGE', 'local');

        $mountManager = new MountManagerFactory();
        $manager = $mountManager->getManager();

        $fromPath = "tmp://{$filename}";
        $toPath = "{$storage}://{$filename}";

        $output->writeln("<info>🇦🇷  Uploading your awesome backup. 🇦🇷</info>");
        $moved = $manager->copy($fromPath, $toPath);

        if ($moved) {
            $output->writeln("<info>Successfully created the backup into {$toPath}.</info>");
        }

    }
}