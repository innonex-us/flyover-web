<?php

namespace Tests\Feature\Admin;

use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WatermarkExistingImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_uploaded_images_are_watermarked_by_the_backfill_command(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        Hotel::create([
            'name' => 'Legacy Hotel',
            'slug' => 'legacy-hotel',
            'location' => 'Dhaka',
            'thumbnail' => 'hotels/legacy-hotel.png',
            'star_rating' => 4,
            'is_active' => true,
        ]);

        $hotelSource = $this->createSolidPng(400, 300, [40, 110, 200]);
        Storage::disk('public')->put('hotels/legacy-hotel.png', file_get_contents($hotelSource));

        $inlineSource = $this->createSolidPng(420, 280, [180, 70, 40]);
        Storage::disk('public')->put('blog/inline/legacy-inline.png', file_get_contents($inlineSource));

        $this->artisan('images:watermark-existing')->assertExitCode(0);

        $this->assertPixelChanged('hotels/legacy-hotel.png', [40, 110, 200], 370, 280);
        $this->assertPixelChanged('blog/inline/legacy-inline.png', [180, 70, 40], 390, 260);

        $hotelAfterFirstRun = Storage::disk('public')->get('hotels/legacy-hotel.png');

        $this->artisan('images:watermark-existing')->assertExitCode(0);

        $this->assertSame($hotelAfterFirstRun, Storage::disk('public')->get('hotels/legacy-hotel.png'));
    }

    private function assertPixelChanged(string $path, array $originalRgb, int $x, int $y): void
    {
        $imagickClass = 'Imagick';
        $this->assertTrue(class_exists($imagickClass));

        $image = new $imagickClass(Storage::disk('public')->path($path));
        $pixel = $image->getImagePixelColor($x, $y)->getColor();
        $image->clear();
        $image->destroy();

        $this->assertNotSame($originalRgb, [
            (int) round($pixel['r'] ?? 0),
            (int) round($pixel['g'] ?? 0),
            (int) round($pixel['b'] ?? 0),
        ]);
    }

    private function createSolidPng(int $width, int $height, array $rgb): string
    {
        $path = tempnam(sys_get_temp_dir(), 'watermark_backfill_') . '.png';
        $image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        imagefilledrectangle($image, 0, 0, $width, $height, $color);
        imagepng($image, $path);
        imagedestroy($image);

        return $path;
    }
}