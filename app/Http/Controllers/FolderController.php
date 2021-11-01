<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Services\FolderService;
use App\Storage\GoogleStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    private FolderService $folderService;

    public function __construct()
    {
        $this->folderService = app(FolderService::class);
    }

    public function index()
    {
        return view('folders.index')->with([
            'folders' => Folder::withCount('files')->get(),
        ]);
    }

    public function create()
    {
        return view('folders.create')->with([
            'folders' => Folder::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all('name', 'parent_folder_id', 'user_id');

        $this->folderService->createFolder($data);

        return redirect()->route('folders.index')->with('status', 'Folder created!');
    }

    public function download(Request $request, Folder $folder)
    {
        //
    }

    public function destroy(Request $request, Folder $folder)
    {
        Storage::disk('google')->delete($folder->google_folder_id);
        $folder->delete();

        return redirect()->route('folders.index')->with('status', 'Folder deleted!');
    }

    public function share(Folder $folder)
    {
      //
    }

    public function move(Folder $folder)
    {
        $this->folderService->moveFolder($folder);

        return redirect()->route('folders.index')->with('status', 'Folder moved!');
    }

    public function copy(Folder $folder)
    {
       //
    }

    public function rename(Folder $folder)
    {
       //
    }
}
