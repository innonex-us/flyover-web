<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageWatermarker
{
    public function watermarkUploadedFile(string $sourcePath, string $storedPath, string $disk = 'public'): void
    {
        $filesystem = Storage::disk($disk);
        $destinationPath = $filesystem->path($storedPath);

        $this->watermarkFile($sourcePath, $destinationPath);
        $this->markProcessed($disk, $storedPath);
    }

    public function watermarkStoredPath(string $storedPath, string $disk = 'public', bool $force = false): bool
    {
        if (! $force && $this->isProcessed($disk, $storedPath)) {
            return false;
        }

        $filesystem = Storage::disk($disk);

        if (! $filesystem->exists($storedPath)) {
            return false;
        }

        $this->watermarkFile($filesystem->path($storedPath), $filesystem->path($storedPath));
        $this->markProcessed($disk, $storedPath);

        return true;
    }

    private function isProcessed(string $disk, string $storedPath): bool
    {
        return Storage::disk('local')->exists($this->markerPath($disk, $storedPath));
    }

    private function markProcessed(string $disk, string $storedPath): void
    {
        Storage::disk('local')->makeDirectory('watermarks');
        Storage::disk('local')->put($this->markerPath($disk, $storedPath), '1');
    }

    private function markerPath(string $disk, string $storedPath): string
    {
        return 'watermarks/' . sha1($disk . ':' . $storedPath) . '.done';
    }

    private function watermarkFile(string $sourcePath, string $destinationPath): void
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