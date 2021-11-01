<?php

namespace App\Services;

use App\Models\File;

class FileService
{
    public function storeToDatabase(array $fileData)
    {
        File::create($fileData);
    }

    public function updateInDatabase(File $file, array $fileData)
    {
        $file->update($fileData);
    }
}
