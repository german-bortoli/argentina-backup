<?php

namespace Argentina\Adapter;

use Argentina\Helper\Env;

class GoogleDriveAdapter
{
    protected $adapter;

    public function __construct()
    {
        // Google API Client
        $client = new \Google_Client();
        $client->setClientId(Env::get('GOOGLE_CLIENT_ID', 'google-client-id'));
        $client->setClientSecret(Env::get('GOOGLE_SECRET_KEY', 'google-secret-key'));
        $client->refreshToken(Env::get('GOOGLE_REFRESH_TOKEN', 'google-refresh-token'));

        $service = new \Google_Service_Drive($client);

        $gDriveAdapter = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter($service, Env::get('GOOGLE_FOLDER_ID', null));

        $this->adapter = new \League\Flysystem\Filesystem($gDriveAdapter);

    }

    public function getAdapter()
    {
        return $this->adapter;
    }
}