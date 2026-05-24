<?php

namespace App\Console\Commands;

use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\Package;
use App\Models\Post;
use App\Models\TransferRoute;
use App\Models\Visa;
use App\Services\ImageWatermarker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class WatermarkExistingImages extends Command
{
    protected $signature = 'images:watermark-existing {--force : Reprocess files even if they were already marked as watermarked}';

    protected $description = 'Apply the site watermark to already uploaded admin images.';

    public function handle(ImageWatermarker $watermarker): int
    {
        $force = (bool) $this->option('force');
        $paths = $this->collectPaths();

        $processed = 0;
        $skipped = 0;
        $missing = 0;

        foreach ($paths as $path) {
            if ($watermarker->watermarkStoredPath($path, 'public', $force)) {
                $processed++;
                $this->line("Watermarked: {$path}");
                continue;
            }

            if (Storage::disk('public')->exists($path)) {
                $skipped++;
                continue;
            }

            $missing++;
            $this->warn("Missing: {$path}");
        }

        $this->info("Done. Processed {$processed}, skipped {$skipped}, missing {$missing}.");

        return self::SUCCESS;
    }

    /**
     * @return array<int, string>
     */
    private function collectPaths(): array
    {
        $paths = collect();

        $paths = $paths->merge(Post::query()->pluck('image'));
        $paths = $paths->merge(Hotel::query()->pluck('thumbnail'));
        $paths = $paths->merge(HotelRoom::query()->pluck('image'));
        $paths = $paths->merge(Visa::query()->pluck('thumbnail'));
        $paths = $paths->merge(TransferRoute::query()->pluck('thumbnail'));
        $paths = $paths->merge(Package::query()->pluck('thumbnail'));

        Package::query()->select('images')->whereNotNull('images')->chunk(100, function ($packages) use (&$paths) {
            foreach ($packages as $package) {
                foreach ((array) $package->images as $image) {
                    $paths->push($image);
                }
            }
        });

        foreach (Storage::disk('public')->allFiles('blog/inline') as $path) {
            $paths->push($path);
        }

        return $paths
            ->filter()
            ->filter(function (string $path) {
                return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
            })
            ->unique()
            ->values()
            ->all();
    }
}