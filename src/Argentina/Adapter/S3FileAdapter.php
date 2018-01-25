<?php

namespace Argentina\Adapter;

use League\Flysystem\Adapter\Local;
use Argentina\Helper\Env;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

class S3FileAdapter
{
    /**
     * @var AwsS3Adapter
     */
    protected $adapter;

    public function __construct()
    {
        $client = S3Client::factory([
            'credentials' => [
                'key' => Env::get('AWS_KEY', ''),
                'secret' => Env::get('AWS_SECRET', ''),
            ],
            'region' => Env::get('AWS_REGION', ''),
            'version' => 'latest',
        ]);

        $s3Adapter = new AwsS3Adapter($client, Env::get('AWS_BUCKET', ''));

        $this->adapter = new \League\Flysystem\Filesystem($s3Adapter);

    }

    public function getAdapter()
    {
        return $this->adapter;
    }
}