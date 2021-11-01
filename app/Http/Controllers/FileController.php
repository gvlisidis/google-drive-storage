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

        return redirect()->route('files.index')->with('status', 'File created!');
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

        return redirect()->route('files.index')->with('status', 'File deleted!');
    }

    public function share(File $file)
    {
        Storage::disk('google')->setVisibility($file->google_file_id, 'private');

        return Storage::disk('google')->url($file->google_file_id);
    }

    public function move(File $file)
    {
        $to = '1RrQXpIwEYnK3cTkn_7bAvSThv8-Wcl82';

        $emptyFileMetadata = new \Google_Service_Drive_DriveFile();
        $service = $this->googleStorage->service();
        // Retrieve the existing parents to remove
        $googleFile  = $service->files->get($file->google_file_id, array('fields' => 'parents'));
        $previousParents = join(',', $googleFile->parents);
        // Move the file to the new folder
        $googleFile = $service->files->update($file->google_file_id, $emptyFileMetadata, array(
            'addParents' => $to,
            'removeParents' => $previousParents,
            'fields' => 'id, parents'));

        return redirect()->route('files.index')->with('status', 'File moved!');
    }

    public function copy(File $file)
    {
        $to = '1rfv8C1XAIEjKHQ-KvxHuVZVxgj65S0xN';
        $from = '1GuO7eYYk6hcZImOUAA7cd1UI7kHn0l3O';


        $fileMetadata = new \Google_Service_Drive_DriveFile();
        $service = $this->googleStorage->service();
        // Retrieve the existing parents to remove

        $fileMetadata->setParents([
            $to
        ]);
        $fileMetadata->setName($file->name);

        $newFile = $service->files->copy($file->google_file_id, $fileMetadata,[
            'fields' => 'id, parents'
        ]);

        $data = [
            'user_id' => null,
            'folder_id' => Folder::query()->where('google_folder_id', $from)->first()->id,
            'name' => $file->name,
            'google_file_id' => $newFile->id
        ];

        $this->fileService->storeToDatabase($data);

        return redirect()->route('files.index')->with('status', 'File copied!');
    }

    public function rename(File $file)
    {
        $newName = 'The Very New Name';
        $fileMetadata = new \Google_Service_Drive_DriveFile();
        $fileMetadata->setName($newName);
        $service = $this->googleStorage->service();
        $service->files->update($file->google_file_id, $fileMetadata,[
            'fields' => 'id'
        ]);

        $data = [
            'user_id' => null,
            'folder_id' => $file->folder_id,
            'name' => $newName,
            'google_file_id' => $file->google_file_id,
        ];

        $this->fileService->updateInDatabase($file, $data);

        return redirect()->route('files.index')->with('status', 'File renamed!');
    }

    public function watch(File $file)
    {
        $fileMetadata = new \Google_Service_Drive_DriveFile();
        $service = $this->googleStorage->service();
        $test = $service->files->watch($file->google_file_id, $fileMetadata,[
            'fields' => 'id'
        ]);

        dd($test);
    }
}
