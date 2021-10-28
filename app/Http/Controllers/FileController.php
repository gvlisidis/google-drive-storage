<?php

namespace App\Http\Controllers;

//use App\Models\File;
use App\Models\Folder;
use App\Services\FileService;
use App\Storage\GoogleStorage;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    private FileService $fileService;
    private GoogleStorage $googleStorage;

    public function __construct()
    {
        $this->fileService = app(FileService::class);
        $this->googleStorage = app(GoogleStorage::class);
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
        $data = $request->only(['folder_id', 'name']);
        $fileName = $data['name'];
        $folderId = $data['folder_id'];
        $file = $request->file('the_file');

        //*********  1st WAY  *************************
        /*
        $service = $this->googleStorage->service();
        $fileMetadata = new \Google_Service_Drive_DriveFile(array(
            'name' => $fileName,
            'parents' => array($folderId)
        ));

        $fileId = $service->files->create($fileMetadata, array(
            'data' => file_get_contents($file),
            'mimeType' => 'application/PDF',
            'uploadType' => 'multipart',
            'fields' => 'id'));
        */

        //*********  2nd WAY  *************************
        $googleFile = Storage::disk('google')->putFileAs($folderId, $file, $fileName);
        dd($folderId, $googleFile);

        $newData = Arr::add($data, 'google_file_id', Str::before($googleFile, '/'));
        $this->fileService->storeToDatabase($newData);

        return redirect()->route('files.index');
    }
}
