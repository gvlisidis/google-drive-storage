<?php

namespace App\Services;

use App\Models\File;

class FileService
{
    public function storeToDatabase(array $fileData)
    {
        File::create([
            'google_file_id' => $fileData['google_file_id'],
            'folder_id' => $fileData['folder_id'],
            'name' => $fileData['file_name'],
        ]);
    }

}
