<?php

use Illuminate\Support\Facades\Route;
use XattaTrone\LaravelBackupUi\Http\Controllers\BackupController;

Route::get('/', [BackupController::class, 'index'])->name('laravel-backups.index');
Route::group(['middleware' => ['signed']], function () {
    Route::get('/download', [BackupController::class, 'download'])->name('laravel-backups.download');
    Route::delete('/destroy', [BackupController::class, 'destroy'])->name('laravel-backups.destroy');
});
