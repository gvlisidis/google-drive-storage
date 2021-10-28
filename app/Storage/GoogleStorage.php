<?php

namespace App\Storage;

class GoogleStorage
{
    private \Google_Client $client;

    public function __construct()
    {
        $this->client = new \Google_Client();
        $this->client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $this->client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
    }

    public function service(): \Google_Service_Drive
    {
        return new \Google_Service_Drive($this->client);
    }

    public function driveFile()
    {
        return new \Google_Service_Drive_DriveFile($this->client);
    }

    public function adapter()
    {
        return new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter($this->service(), env('GOOGLE_DRIVE_FOLDER_ID'));
    }

    public function drivePermission()
    {
        return new \Google_Service_Drive_Permission();
    }
}
