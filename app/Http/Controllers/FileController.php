<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Services\FileService;
use App\Storage\GoogleStorage;
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
        $data = $request->only(['folder_id', 'name', 'user_id']);
        $fileName = $data['name'];
        $folderId = $data['folder_id'];
        $googleFolderId = Folder::find($folderId)->google_folder_id;
        $file = $request->file('the_file');

        //*********  1st WAY  *************************

        $service = $this->googleStorage->service();
        $fileMetadata = new \Google_Service_Drive_DriveFile(array(
            'name' => $fileName,
            'parents' => array($googleFolderId)
        ));

        $googleFile = $service->files->create($fileMetadata, array(
            'data' => file_get_contents($file),
            'mimeType' => 'application/PDF',
            'uploadType' => 'multipart',
            'fields' => 'id'));


        //*********  2nd WAY  *************************
        //$googleFile = Storage::disk('google')->putFileAs($googleFolderId, $file, $fileName);

        $newData = Arr::add($data, 'google_file_id', $googleFile->id);

        $this->fileService->storeToDatabase($newData);

        return redirect()->route('files.index');
    }

    public function download(Request $request, File $file)
    {
        $response = Storage::disk('google')->download($file->google_file_id, $file->name . '.pdf', ['Content-Type' => 'application/pdf']);
        $response->send();
    }

    public function destroy(Request $request, File $file)
    {
        Storage::disk('google')->delete($file->google_file_id);
        $file->delete();

        return redirect()->route('files.index');
    }

    public function share(File $file)
    {
        Storage::disk('google')->setVisibility($file->google_file_id, 'private');

        return Storage::disk('google')->url($file->google_file_id);
    }

    public function move(File $file)
    {
      //  dd($file);
        $from = '1he-u_z_0VYYEHdsClZmhc-RS3aQobaaJ';
        $to = '1o-sKX0Wwed3olIRZv7dohpYxdFbus3Xq/1R5ORBkaYf3tO6rEsCEqZk7bb6uyHntdp';

        $emptyFileMetadata = new \Google_Service_Drive_DriveFile();
        $service = $this->googleStorage->service();
        // Retrieve the existing parents to remove
        $service->files->get($file->google_file_id, array('fields' => 'parents'));
       // $previousParents = join(',', $from);
        // Move the file to the new folder
        $service->files->update($file->google_file_id, $emptyFileMetadata, array(
            'addParents' => $to,
            'removeParents' => $from,
            'fields' => 'id, parents'));

        return redirect()->route('files.index');
    }
}
