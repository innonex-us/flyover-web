<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $path = $request->file('file')->store('blog/inline', 'public');

        return response()->json([
            'error'   => false,
            'message' => 'Uploaded successfully',
            'files'   => [Storage::url($path)],
        ]);
    }
}
