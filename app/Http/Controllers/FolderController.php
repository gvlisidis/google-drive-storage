<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Services\FolderService;
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
            'folders' => Folder::all(),
        ]);
    }

    public function create()
    {
        return view('folders.create');
    }

    public function store(Request $request)
    {
        Storage::disk('google')->makeDirectory($request->folder_name);
        $this->folderService->mapFolder(Storage::disk('google')->directories());

        return redirect()->route('folders.index');
    }
}
