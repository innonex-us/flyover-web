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
        $this->storeWatermarkedImage($file->getRealPath(), Storage::disk('public')->path($path));

        return response()->json([
            'error'   => false,
            'message' => 'Uploaded successfully',
            'files'   => [Storage::url($path)],
        ]);
    }

    private function storeWatermarkedImage(string $sourcePath, string $destinationPath): void
    {
        $watermarkPath = public_path('logo.png');

        if (! file_exists($watermarkPath)) {
            throw new \RuntimeException('Watermark logo not found.');
        }

        $imagickClass = 'Imagick';

        if (! class_exists($imagickClass)) {
            throw new \RuntimeException('Imagick extension is required for image watermarking.');
        }

        $image = new $imagickClass();
        $image->readImage($sourcePath);

        try {
            if (strtolower($image->getImageFormat()) === 'gif' && $image->getNumberImages() > 1) {
                $this->watermarkAnimatedGif($image, $watermarkPath, $destinationPath, $imagickClass);
                return;
            }

            $this->watermarkStillImage($image, $watermarkPath, $destinationPath, $imagickClass);
        } finally {
            $image->clear();
            $image->destroy();
        }
    }

    private function watermarkStillImage($image, string $watermarkPath, string $destinationPath, string $imagickClass): void
    {
        $watermark = new $imagickClass($watermarkPath);
        $this->prepareWatermark($image, $watermark, $imagickClass);

        $margin = max(16, (int) round($image->getImageWidth() * 0.04));
        $x = max($margin, $image->getImageWidth() - $watermark->getImageWidth() - $margin);
        $y = max($margin, $image->getImageHeight() - $watermark->getImageHeight() - $margin);

        $image->compositeImage($watermark, constant($imagickClass . '::COMPOSITE_OVER'), $x, $y);
        $image->writeImage($destinationPath);

        $watermark->clear();
        $watermark->destroy();
    }

    private function watermarkAnimatedGif($image, string $watermarkPath, string $destinationPath, string $imagickClass): void
    {
        $frames = $image->coalesceImages();
        $output = new $imagickClass();

        foreach ($frames as $frame) {
            $watermark = new $imagickClass($watermarkPath);
            $this->prepareWatermark($frame, $watermark, $imagickClass);

            $margin = max(16, (int) round($frame->getImageWidth() * 0.04));
            $x = max($margin, $frame->getImageWidth() - $watermark->getImageWidth() - $margin);
            $y = max($margin, $frame->getImageHeight() - $watermark->getImageHeight() - $margin);

            $frame->setImagePage(0, 0, 0, 0);
            $frame->compositeImage($watermark, constant($imagickClass . '::COMPOSITE_OVER'), $x, $y);
            $output->addImage(clone $frame);

            $watermark->clear();
            $watermark->destroy();
        }

        $output->writeImages($destinationPath, true);

        $frames->clear();
        $frames->destroy();
        $output->clear();
        $output->destroy();
    }

    private function prepareWatermark($image, $watermark, string $imagickClass): void
    {
        $maxWidth = max(96, (int) round($image->getImageWidth() * 0.18));
        $originalWidth = max(1, $watermark->getImageWidth());
        $targetHeight = max(1, (int) round($watermark->getImageHeight() * ($maxWidth / $originalWidth)));

        $watermark->resizeImage($maxWidth, $targetHeight, constant($imagickClass . '::FILTER_LANCZOS'), 1);
        $watermark->setImageAlphaChannel(constant($imagickClass . '::ALPHACHANNEL_ACTIVATE'));
        $watermark->evaluateImage(constant($imagickClass . '::EVALUATE_MULTIPLY'), 0.55, constant($imagickClass . '::CHANNEL_ALPHA'));
        $watermark->setImageFormat('png');
    }
}
