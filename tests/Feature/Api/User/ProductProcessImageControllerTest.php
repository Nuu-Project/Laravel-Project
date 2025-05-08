<?php

namespace Tests\Feature\Api\User;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductProcessImageControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsUser();
    }

    public function test_it_processes_image_successfully()
    {
        Storage::fake('temp');

        $file = $this->fakeImage();

        $response = $this->processImage([
            'image' => $file,
        ])->assertOk()
            ->assertJson([
                'success' => true,
                'message' => '圖片上傳成功',
            ]);

        $encryptedPath = $response->json('path');
        $this->assertNotNull($encryptedPath);

        $decryptedPath = decrypt($encryptedPath);
        Storage::disk('temp')->assertExists($decryptedPath);
    }

    public function test_it_fails_when_image_is_missing()
    {
        $this->processImage([])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    }

    public function test_it_fails_when_image_type_is_invalid()
    {
        $file = UploadedFile::fake()->create('file.txt', 100, 'text/plain');

        $this->processImage([
            'image' => $file,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    }

    public function test_it_fails_when_image_size_is_too_large()
    {
        $file = $this->fakeImage('big.jpg')->size(3000);

        $this->processImage([
            'image' => $file,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    }

    public function test_it_processes_very_small_image_successfully()
    {
        Storage::fake('temp');

        // 建立一個非常小的假圖片 (例如 1KB)
        $file = $this->fakeImage('small.jpg', 100, 100, 1);

        $response = $this->processImage([
            'image' => $file,
        ])->assertOk()
            ->assertJson([
                'success' => true,
                'message' => '圖片上傳成功',
            ]);

        $encryptedPath = $response->json('path');
        $this->assertNotNull($encryptedPath);

        $decryptedPath = decrypt($encryptedPath);
        Storage::disk('temp')->assertExists($decryptedPath);
    }

    public function test_it_fails_when_image_dimensions_are_too_large()
    {
        $file = $this->fakeImage('large.jpg', 4000, 4000);

        $this->processImage([
            'image' => $file,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    }

    public function test_it_fails_when_image_type_is_gif()
    {
        Storage::fake('temp');

        $file = $this->fakeImage('animated.gif', 800, 600);

        $this->processImage([
            'image' => $file,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);

        Storage::disk('temp')->assertMissing('compressed_');
    }

    public function test_it_fails_when_user_is_not_authenticated()
    {
        $this->logout();

        $file = $this->fakeImage();

        $this->processImage(['image' => $file])
            ->assertUnauthorized();
    }

    private function processImage(array $data): \Illuminate\Testing\TestResponse
    {
        return $this->postJson(route('api.products.process-image'), $data);
    }

    private function fakeImage(string $name = 'test.jpg', int $width = 800, int $height = 800, int $sizeInKB = 500): UploadedFile
    {
        return UploadedFile::fake()->image($name, $width, $height)->size($sizeInKB);
    }
}
