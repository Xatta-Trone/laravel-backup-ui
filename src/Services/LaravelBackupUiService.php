<?php

namespace XattaTrone\LaravelBackupUi\Services;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;


class LaravelBackupUiService
{
    public static function getBackups(string $disk, string $dir, int $page = 1, int $limit = 10, string $sort = 'asc'): array
    {
        $files = Storage::disk($disk)->files($dir);
        $filesPrefix = config('backup.backup.destination.filename_prefix', "");
        $pattern = '/^' . preg_quote($filesPrefix, '/') . '\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2}\.zip$/';
        $filteredFiles = [];

        foreach ($files as $file) {
            $fileMeta = Storage::disk($disk)->getMetadata($file);
            $fileName = array_key_exists('name', $fileMeta) ? $fileMeta['name'] :  $fileMeta['path'];
            $fileSize = array_key_exists('size', $fileMeta) ? $fileMeta['size'] : null;
            $fileDate = array_key_exists('timestamp', $fileMeta) ? $fileMeta['timestamp'] : null;

            if (preg_match($pattern, basename($fileName))) {
                $filteredFiles[] = [
                    'file_id' => $file,
                    'name' => $fileName,
                    'size' => $fileSize ? number_format($fileSize / (1024 * 1024), 2) . "MB" : null,
                    'last_modified' => $fileDate,
                    'disk' => $disk,
                    'download_url' => URL::signedRoute('laravel-backups.download', ['filename' => $file, 'disk' => $disk]),
                    'delete_url' => URL::signedRoute('laravel-backups.destroy', ['filename' => $file, 'disk' => $disk]),
                ];
            }
        }


        // Sort files by date (ascending order)
        $sortOrder = isset($_GET['sort']) && $_GET['sort'] === 'desc' ? -1 : 1;
        usort($filteredFiles, function ($a, $b) use ($sortOrder, $disk) {
            return $sortOrder * ($a['last_modified'] - $b['last_modified']);
        });

        // Pagination settings
        $totalFiles = count($filteredFiles);
        $totalPages = ceil($totalFiles / $limit);

        // Calculate the offset
        $offset = ($page - 1) * $limit;

        // Get the files for the current page
        $slicedFilesResult = array_slice($filteredFiles, $offset, $limit);

        return [
            'items' => $slicedFilesResult,
            'total_items' => $totalFiles,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'limit' => $limit,
            'sort' => $sort,
            'disk' => $disk,
        ];
    }
}
