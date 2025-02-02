<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StorageServiceInterface;
 
class ListFiles extends Controller
{
    protected $storageService;

    public function __construct(StorageServiceInterface $storageService)
    {
        $this->storageService = $storageService;
    }

    public function __invoke(Request $request)
    {
        return $this->storageService->getFiles();
    }
}
