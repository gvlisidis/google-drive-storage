<?php

namespace App\Services;

use App\Models\Folder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class FolderService
{
    public function mapFolder(array $dirs)
    {
        $googleID = Arr::last($dirs);
        $newFolder = Storage::disk('google')->getMetadata($googleID);

        Folder::create([
            'google_folder_id' => $googleID,
            'parent_folder_id' => 1,
            'name' => $newFolder['name']
        ]);
    }
}
