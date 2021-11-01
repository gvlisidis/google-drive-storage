<?php

namespace App\Services;

use App\Models\Folder;
use App\Storage\GoogleStorage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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

    public function downloadFolder(Folder $folder)
    {
        return true;
    }

    public function moveFolder(Folder $folder)
    {
        $to = '121UVv0dkKwOMhwf5B_L8s1mw7YP15QBC';

        $emptyFileMetadata = new \Google_Service_Drive_DriveFile();
        $service = $this->googleStorage->service();
        // Retrieve the existing parents to remove
        $googleFolder  = $service->files->get($folder->google_folder_id, array('fields' => 'parents'));
        $previousParents = join(',', $googleFolder->parents);
        // Move the file to the new folder
        $googleFile = $service->files->update($folder->google_folder_id, $emptyFileMetadata, array(
            'addParents' => $to,
            'removeParents' => $previousParents,
            'fields' => 'id, parents'));
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
