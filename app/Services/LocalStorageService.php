<?php

namespace App\Services;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

class LocalStorageService implements StorageServiceInterface
{
    protected $adapter;
    protected $filesystem;
    public function __construct()
    {
        $this->adapter = new LocalFilesystemAdapter(
            storage_path('app/public')
        );
        $this->filesystem = new Filesystem($this->adapter);
    }
    public function uploadFile($file, $fileName)
    {
        $fileContent = file_get_contents($file->getRealPath());

        try {
            $this->filesystem->write($fileName, $fileContent);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'File upload failed',
                'error' => $e->getMessage(),
            ];
        }
        return [
            'success' => true,
            'message' => 'File uploaded successfully',
            'url' => "/storage/" . $fileName,
        ];
    }
}
