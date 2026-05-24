<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_uploads_image_with_watermark(): void
    {
        Storage::fake('public');

        /** @var User $admin */
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $sourcePath = $this->createSolidPng(400, 300, [30, 120, 220]);
        $file = new UploadedFile($sourcePath, 'sample.png', 'image/png', null, true);

        $response = $this->actingAs($admin)->postJson(route('admin.upload.image'), [
            'file' => $file,
        ]);

        $response->assertOk();
        $response->assertJson([
            'error' => false,
            'message' => 'Uploaded successfully',
        ]);

        $storedFile = collect(Storage::disk('public')->allFiles('blog/inline'))->first();

        $this->assertNotNull($storedFile);
    $this->assertTrue(Storage::disk('public')->exists($storedFile));

        $imagickClass = 'Imagick';
        $this->assertTrue(class_exists($imagickClass));

        $image = new $imagickClass(Storage::disk('public')->path($storedFile));
        $pixel = $image->getImagePixelColor(370, 280)->getColor();
        $image->clear();
        $image->destroy();

        $this->assertNotSame([30, 120, 220], [
            (int) round($pixel['r'] ?? 0),
            (int) round($pixel['g'] ?? 0),
            (int) round($pixel['b'] ?? 0),
        ]);
    }

    private function createSolidPng(int $width, int $height, array $rgb): string
    {
        $path = tempnam(sys_get_temp_dir(), 'watermark_') . '.png';
        $image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        imagefilledrectangle($image, 0, 0, $width, $height, $color);
        imagepng($image, $path);
        imagedestroy($image);

        return $path;
    }
}
