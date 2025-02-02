<?php

namespace App\Services;

use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use League\Flysystem\Filesystem;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;


class AzureStorageService implements StorageServiceInterface
{
    protected $url;
    protected $sasToken;
    protected $client;
    protected $adapter;
    protected $filesystem;

    public function __construct()
    {
        $this->url = env('BLOB_ENDPOINT');

        $this->client = BlobRestProxy::createBlobService(env('CONNECTION_STRING'));
        $this->adapter = new AzureBlobStorageAdapter(
            $this->client,
            'apl-recruitment-images',
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

        $returnUrl = "{$this->url}/apl-recruitment-images/{$fileName}";
        return [
            'success' => true,
            'message' => 'File uploaded successfully',
            'url' => $returnUrl,
        ];
    }
}
