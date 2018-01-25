<?php

namespace Argentina\Adapter;

use League\Flysystem\Adapter\Local;
use Argentina\Helper\Env;

class LocalFileAdapter
{
    /**
     * @var Local
     */
    protected $adapter;

    protected $tmpAdapter;

    public function __construct()
    {
        $tmpDir = rtrim(Env::getTmpDirectory(), DIRECTORY_SEPARATOR);

        $path = Env::get('BACKUP_DIRECTORY', $tmpDir);
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        $localAdapter = new Local($path);

        $this->adapter = new \League\Flysystem\Filesystem($localAdapter);
        $this->tmpAdapter = new \League\Flysystem\Filesystem(new Local($tmpDir));

    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function getTempAdapter()
    {
        return $this->tmpAdapter;
    }
}