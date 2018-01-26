<?php
/**
 * Used to logrotate the backups
 */

namespace Argentina\Process;

use Argentina\Factory\MountManagerFactory;
use Argentina\Helper\Env;


class ClearProcess
{
    public static function run()
    {
        $storage = Env::get('BACKUP_STORAGE', 'local');

        $mountManager = new MountManagerFactory();
        $manager = $mountManager->getManager();

        $files = $manager->listContents("{$storage}://");

        uasort($files, function ($a, $b) {
            return $a['timestamp'] < $b['timestamp'];
        });

        $to_keep = Env::get('BACKUPS_TO_KEEP', false);
        $filePrefix = Env::get('FILENAME_PREFIX', false);

        if ($to_keep == false) {
            return true;
        }

        $backups = [];
        $deleted = [];
        $count_files = 0;

        $fClearPrefix = function ($node) use ($filePrefix) {

            if (!$filePrefix) {
                return null;
            }

            if (strpos($node['filename'], $filePrefix) === 0) {
                return true;
            }

            return false;
        };

        foreach ($files as $node) {
            if ($node['type'] !== 'file') {
                continue;
            }

            $hasPrefix = $fClearPrefix($node);

            if ($hasPrefix === false) {
                continue;
            }

            if ($count_files >= $to_keep) {
                array_push($deleted, $node);
            } else {
                array_push($backups, $node);
            }

            $count_files++;
        }

        if (empty($deleted)) {
            return true;
        }

        foreach ($deleted as $to_delete) {
            $manager->delete("{$storage}://{$to_delete['path']}");
        }
    }
}