<?php

namespace App\Services;

use App\Models\File;

class FileService
{
    public function storeToDatabase(array $fileData)
    {
        File::create($fileData);
    }

}
