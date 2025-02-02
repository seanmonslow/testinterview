<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use App\Services\StorageServiceInterface;
use Illuminate\Support\Facades\DB;

class ImageUploadController extends Controller
{
    protected $storageService;

    public function __construct(StorageServiceInterface $storageService)
    {
        $this->storageService = $storageService;
    }

    public function __invoke(Request $request)
    {   
        $request->validate([
            'attachment' => [
                'required',
                File::types(['jpg', 'png']),
                'mimes:jpeg,png',
                'dimensions:max_width=1024,max_height=1024',
            ],
        ]);

        $file = $this->storageService->uploadFile($request->file('attachment'), $request->file('attachment')->hashName());

        if ($file['success']) {
            $imageData = $request->file('attachment')->get();
            $im = imagecreatefromstring($imageData);
            $width = imagesx($im);
            $height = imagesy($im);
            DB::table('images')->insert([
                'public_url' => $file['url'],
                'height' => $height,
                'width' => $width,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        return view('home', ['file' => $file]);
    }
}
