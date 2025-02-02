<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AzureStorageService;
use App\Services\LocalStorageService;
use App\Services\StorageServiceInterface;

class StorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(StorageServiceInterface::class, function ($app) {
            $storageDriver = env('STORAGE_DRIVER', 'local');

            if ($storageDriver === 'azure') {
                return new AzureStorageService();
            } else {
                return new LocalStorageService();
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        //
    }
}
