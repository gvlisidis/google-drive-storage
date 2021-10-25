<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    private FileService $fileService;

    public function __construct()
    {
        $this->fileService = app(FileService::class);
    }

    public function index()
    {
        $files = File::all();
        return view('files.index')->with([
            'files' => $files,
        ]);
    }

    public function create()
    {

        $folders = Folder::all();
        return view('files.create')->with([
            'folders' => $folders,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->only(['folder_id', 'file_name']);

        $fileName = $data['file_name'];
        $folder = Folder::find($data['folder_id']);


        $file = Storage::disk('google')->putFileAs($folder->google_folder_id, $request->file('the_file'), $fileName);
        $newData = Arr::add($data, 'google_file_id', Str::before($file, '/'));
        $this->fileService->storeToDatabase($newData);

        return redirect()->route('files.index');
    }
}
