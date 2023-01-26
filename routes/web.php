<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlockchainController;
use App\Http\Controllers\FileController;
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

Route::get('/dashboard', [FileController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'nocache'])->group(function () {
    Route::get('/files/crc/post/{file}', [FileController::class, 'postcrc'])->name('files.crc.post');
    Route::get('/files/error', [FileController::class, 'error'])->name('files.error');
});

Route::middleware('auth')->group(function () {
    Route::resource('files', FileController::class)->only(['index', 'store'])
        ->names(['index'=>'files.index', 'store'=>'files.upload']);
    Route::post('/files/delete/{file}', [FileController::class, 'delete'])->name('files.delete');
    Route::post('/files/download/{file}', [FileController::class, 'download'])->name('files.download');
    Route::post('/blockchain/register', [BlockchainController::class, 'register'])->name('blockchain.register');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
