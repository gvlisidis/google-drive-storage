<?php

namespace App\Services;

use App\Models\Folder;
use App\Storage\GoogleStorage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class FolderService
{
    private GoogleStorage $googleStorage;

    public function __construct()
    {
        $this->googleStorage = app(GoogleStorage::class);
    }

    public function createFolder(array $data)
    {
        $service = $this->googleStorage->service();

        $dataForGoogleService = $this->prepareDataForGoogleService($data);

        $googleFolderId = $this->createGoogleFolder($dataForGoogleService, $service);

        Folder::create([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'parent_folder_id' => $data['parent_folder_id'] ?: 1,
            'google_folder_id' => $googleFolderId->id
        ]);
    }

    private function createGoogleFolder(array $data, $service)
    {
        $fileMetadata = new \Google_Service_Drive_DriveFile($data);

        return $service->files->create($fileMetadata, [
            'fields' => 'id'
        ]);
    }

    private function prepareDataForGoogleService(array $data): array
    {
        return [
            'name' => $data['name'],
            'parents' => [$this->getParentFolderGoogleId($data['parent_folder_id'])],
            'mimeType' => 'application/vnd.google-apps.folder'
        ];
    }

    private function getParentFolderGoogleId(int|null $folderId): string
    {
        if ($folderId) {
            return Folder::query()
                ->select('google_folder_id')
                ->where('id', $folderId)
                ->first()
                ->google_folder_id;
        }

        return env('GOOGLE_DRIVE_FOLDER_ID');
    }
}
