<?php

namespace App\Services;

interface StorageServiceInterface
{
    public function uploadFile($file, $fileName);
}