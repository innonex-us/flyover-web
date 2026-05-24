<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $path = 'blog/inline/' . Str::uuid() . '.' . $extension;

        Storage::disk('public')->makeDirectory('blog/inline');
        app(\App\Services\ImageWatermarker::class)->watermarkUploadedFile($file->getRealPath(), $path);

        return response()->json([
            'error'   => false,
            'message' => 'Uploaded successfully',
            'files'   => [Storage::url($path)],
        ]);
    }
}
