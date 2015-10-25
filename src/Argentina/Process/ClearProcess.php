<?php
/**
 * Used to logrotate the backups
 */

namespace Argentina\Process;
use Argentina\Helper\Env;


class ClearProcess
{
    public static function run()
    {
        $path = Env::get('BACKUP_DIRECTORY');
        $to_keep = Env::get('BACKUPS_TO_KEEP', false);

        if ($to_keep == false) {
            return true;
        }

        if (!$path) {
            throw new ProcessException("Backup directory should be configured");
        }

        if (!is_dir($path)) {
            throw new ProcessException("Backup directory is wrong or does not exists.");
        }

        $path = rtrim($path, DIRECTORY_SEPARATOR);
        $scanned = scandir($path, SCANDIR_SORT_DESCENDING);

        $backups = [];
        $deleted = [];
        $count_files = 0;

        foreach ($scanned as $node) {

            if ($node == '.' || $node  == '..') {
                continue;
            }

            if (strpos($node, '.') === 0) {
                continue;
            }

            $nodePath = $path . DIRECTORY_SEPARATOR . $node;
            if (is_dir($nodePath)) continue;

            if ($count_files >= $to_keep) {
                array_push($deleted, $nodePath);
            } else {
                array_push($backups, $nodePath);
            }

            $count_files++;
        }

        if (empty($deleted)) {
            return true;
        }

        // delete old files
        foreach ($deleted as $to_delete) {
            unlink($to_delete);
        }
    }
}