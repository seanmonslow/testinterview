<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\StorageServiceInterface;
use Mockery;
use Mockery\MockInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class IndexTest extends TestCase
{
    public function test_the_application_returns_a_successful_response_when_posting_an_image(): void
    {
        $mock = Mockery::mock(StorageServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('uploadFile')->once()->andReturn([
                'success' => true,
                'url' => 'http://example.com/test.jpg',
            ]);
        });

        $builder = Mockery::mock(Builder::class);

        $builder->shouldReceive('insert')
            ->once()
            ->andReturn(true); // Simulate successful insert

        DB::shouldReceive('table')
            ->with('images')
            ->andReturn($builder);

        $this->app->instance(StorageServiceInterface::class, $mock);

        $response = $this->post('/', [
            'attachment' => UploadedFile::fake()->image('test.jpg', 1024, 1024)
        ]);

        $response->assertStatus(200);
    }

    public function test_the_application_returns_a_not_found_response_when_posting_not_an_image(): void
    {
        $response = $this->post('/', [
            'attachment' => UploadedFile::fake()->image('test.mkv')
        ]);

        $response->assertStatus(302);
    }

    public function test_the_application_returns_a_failed_response_when_posting_an_image(): void
    {
        $mock = Mockery::mock(StorageServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('uploadFile')->once()->andReturn([
                'success' => false,
            ]);
        });

        $builder = Mockery::mock(Builder::class);

        $builder->shouldReceive('insert')
            ->never()
            ->andReturn(true); // Simulate successful insert

        DB::shouldReceive('table')
            ->never();

        $this->app->instance(StorageServiceInterface::class, $mock);

        $response = $this->post('/', [
            'attachment' => UploadedFile::fake()->image('test.jpg', 1024, 1024)
        ]);

        $response->assertStatus(200);
    }
}
