<?php

namespace Argentina\Factory;

use Argentina\Adapter\GoogleDriveAdapter;
use Argentina\Adapter\LocalFileAdapter;
use Argentina\Adapter\S3FileAdapter;

class MountManagerFactory
{
    protected $manager;

    public function __construct()
    {
        $s3Adapter = new S3FileAdapter();
        $localAdapter = new LocalFileAdapter();
        $gDriveAdapter = new GoogleDriveAdapter();
        // Add them in the constructor
        $this->manager = new \League\Flysystem\MountManager([
            'tmp' => $localAdapter->getTempAdapter(),
            'local' => $localAdapter->getAdapter(),
            's3' => $s3Adapter->getAdapter(),
            'gdrive' => $gDriveAdapter->getAdapter(),
        ]);
    }

    public function getManager()
    {
        return $this->manager;
    }
}