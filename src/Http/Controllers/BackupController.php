<?php

namespace XattaTrone\LaravelBackupUi\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use XattaTrone\LaravelBackupUi\Services\LaravelBackupUiService;
use XattaTrone\LaravelBackupUi\Http\Requests\LaravelBackupUiIndexRequest;
use XattaTrone\LaravelBackupUi\Http\Requests\LaravelBackupUiDownloadRequest;

class BackupController extends Controller
{
    public $dir;
    public $disks;
    public function __construct()
    {
        $this->dir = config('backup.backup.name');
        $this->disks = config('backup.backup.destination.disks', ['local']);
    }

    public function index(LaravelBackupUiIndexRequest $request)
    {
        $disk = $request->input('disk') ?: $this->disks[0];

        $results = LaravelBackupUiService::getBackups(
            $disk,
            $this->dir,
            $request->input('page'),
            $request->input('per_page'),
            $request->input('sort'),
        );

        $paginate = new LengthAwarePaginator(
            $results['items'],
            $results['total_items'],
            $request->input('per_page'),
            LengthAwarePaginator::resolveCurrentPage(),
            array('path' => LengthAwarePaginator::resolveCurrentPath())
        );
        return view(
            'xatta-trone::laravel-backups.' . config('laravel-backup-ui.theme', 'bootstrap-4'),
            ['paginate' => $paginate, 'disks' => $this->disks]
        );
    }

    public function download(LaravelBackupUiDownloadRequest $request)
    {
        try {
            $subPath = $this->dir . '/' . $request->input('filename');
            return Storage::disk($request->input('disk'))->download($subPath);
        } catch (Exception $e) {
            return redirect()->back()->with('danger', 'Error downloading file: ' . $e->getMessage());
        }
    }

    public function destroy(LaravelBackupUiDownloadRequest $request)
    {
        try {
            $subPath = $this->dir . '/' . $request->input('filename');
            Storage::disk($request->input('disk'))->delete($subPath);
            return redirect()->back()->with('success', 'File deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('danger', 'Error deleting file: ' . $e->getMessage());
        }
    }
}
