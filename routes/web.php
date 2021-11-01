<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('folders')->as('folders.')->group(function (){
    Route::get('', [FolderController::class, 'index'])->name('index');
    Route::get('/create-folder', [FolderController::class, 'create'])->name('create');
    Route::post('', [FolderController::class, 'store'])->name('store');
    Route::post('/{folder}/download', [FolderController::class, 'download'])->name('download');
    Route::delete('/{folder}/delete', [FolderController::class, 'destroy'])->name('delete');
    Route::get('/{folder}/share', [FolderController::class, 'share'])->name('share');
    Route::get('/{folder}/move', [FolderController::class, 'move'])->name('move');
    Route::get('/{folder}/copy', [FolderController::class, 'copy'])->name('copy');
    Route::get('/{folder}/rename', [FolderController::class, 'rename'])->name('rename');
});

Route::prefix('files')->as('files.')->group(function (){
    Route::get('', [FileController::class, 'index'])->name('index');
    Route::get('/create-file', [FileController::class, 'create'])->name('create');
    Route::post('', [FileController::class, 'store'])->name('store');
    Route::post('/{file}/download', [FileController::class, 'download'])->name('download');
    Route::delete('/{file}/delete', [FileController::class, 'destroy'])->name('delete');
    Route::get('/{file}/share', [FileController::class, 'share'])->name('share');
    Route::get('/{file}/move', [FileController::class, 'move'])->name('move');
    Route::get('/{file}/copy', [FileController::class, 'copy'])->name('copy');
    Route::get('/{file}/rename', [FileController::class, 'rename'])->name('rename');
    Route::get('/{file}/watch', [FileController::class, 'watch'])->name('watch');
});
