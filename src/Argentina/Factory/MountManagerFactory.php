<?php

namespace Argentina\Factory;

use Argentina\Adapter\LocalFileAdapter;
use Argentina\Adapter\S3FileAdapter;

class MountManagerFactory
{
    protected $manager;

    public function __construct()
    {
        $s3Adapter = new S3FileAdapter();
        $localAdapter = new LocalFileAdapter();

        // Add them in the constructor
        $this->manager = new \League\Flysystem\MountManager([
//            'ftp' => $ftp,
            's3' => $s3Adapter->getAdapter(),
            'local' => $localAdapter->getAdapter(),
            'tmp' => $localAdapter->getTempAdapter(),
        ]);
    }

    public function getManager()
    {
        return $this->manager;
    }
}