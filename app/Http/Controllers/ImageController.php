<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadImageRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image as InterImage;
use Str;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $session, UploadImageRequest $request)
    {
        sleep(2);
        $upload = $request->file('image');
        $quality = $request->get('quality');

        $image = InterImage::read($upload);

        if (!file_exists(storage_path('app/public/' . $session))) {
            mkdir(storage_path('app/public/' . $session));
        }

        $path = join('/', [
            $session,
            $quality . '_' . $upload->getClientOriginalName(),
        ]);

        if (file_exists(storage_path('app/public/' . $path))) {
            $filesize = filesize(storage_path('app/public/' . $path));

            return response()->json([
                'uri' => asset($path),
                'size' => $filesize,
                'percentage' => floor($filesize * 100 / $upload->getSize()),
            ]);
        }

        $compressedImage = $image->encodeByExtension($upload->getClientOriginalExtension(), quality: (int)$quality);

        Storage::disk('public')->put(
            $path,
            $compressedImage,
        );

        Image::create([
            'name' => $upload->getClientOriginalName(),
            'size' => $upload->getSize(),
            'uri' => $path,
        ]);

        return response()->json([
            'uri' => asset($path),
            'size' => $compressedImage->size(),
            'percentage' => floor($compressedImage->size() * 100 / $upload->getSize()),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //
    }
}
