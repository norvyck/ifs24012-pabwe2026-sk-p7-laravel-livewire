<?php

namespace Tests\Unit\Services;

use App\Features\Stock\Enums\StockStatus;
use App\Features\Stock\Models\Stock;
use App\Features\Stock\Repositories\StockRepository;
use App\Features\Stock\Services\StockService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    protected StockService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StockService(new StockRepository());
        Storage::fake('public');
    }

    public function test_get_stocks_for_user(): void
    {
        $user = User::factory()->create();
        Stock::factory()->count(3)->create(['user_id' => $user->id]);

        $stocks = $this->service->getStocksForUser($user->id);
        $this->assertCount(3, $stocks);
    }

    public function test_create_stock(): void
    {
        $user = User::factory()->create();

        $stock = $this->service->createStock($user->id, [
            'name' => 'Kertas A4',
            'sku' => 'A4-01',
            'description' => 'Kertas HVS',
            'quantity' => 10,
            'unit' => 'rim',
            'price' => 50000,
            'min_stock' => 5,
        ]);

        $this->assertInstanceOf(Stock::class, $stock);
        $this->assertEquals('Kertas A4', $stock->name);
        $this->assertEquals('available', $stock->status);
    }

    public function test_update_stock(): void
    {
        $user = User::factory()->create();
        $stock = Stock::factory()->create(['user_id' => $user->id, 'quantity' => 10, 'min_stock' => 5]);

        $result = $this->service->updateStock($stock, [
            'name' => 'Updated Item',
            'quantity' => 2,
        ]);

        $this->assertTrue($result);
        $stock->refresh();
        $this->assertEquals('Updated Item', $stock->name);
        $this->assertEquals(2, $stock->quantity);
        $this->assertEquals(StockStatus::LOW_STOCK->value, $stock->status);
    }

    public function test_adjust_quantity(): void
    {
        $user = User::factory()->create();
        $stock = Stock::factory()->create([
            'user_id' => $user->id,
            'quantity' => 6,
            'min_stock' => 5,
            'status' => 'available',
        ]);

        // Decrement by 1 -> qty 5 -> low_stock
        $this->service->adjustQuantity($stock, -1);
        $stock->refresh();
        $this->assertEquals(5, $stock->quantity);
        $this->assertEquals('low_stock', $stock->status);

        // Decrement by 5 -> qty 0 -> out_of_stock
        $this->service->adjustQuantity($stock, -5);
        $stock->refresh();
        $this->assertEquals(0, $stock->quantity);
        $this->assertEquals('out_of_stock', $stock->status);

        // Increment by 10 -> qty 10 -> available
        $this->service->adjustQuantity($stock, 10);
        $stock->refresh();
        $this->assertEquals(10, $stock->quantity);
        $this->assertEquals('available', $stock->status);
    }

    public function test_cover_photo_management(): void
    {
        $user = User::factory()->create();
        $stock = Stock::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->image('cover.jpg', 600, 400);

        // Upload
        $path = $this->service->updateCoverPhoto($stock, $file);
        $stock->refresh();
        $this->assertEquals($path, $stock->cover_image_path);
        Storage::disk('public')->assertExists($path);

        // Replace with new photo
        $newFile = UploadedFile::fake()->image('new_cover.jpg', 600, 400);
        $newPath = $this->service->updateCoverPhoto($stock, $newFile);
        $stock->refresh();
        $this->assertEquals($newPath, $stock->cover_image_path);
        Storage::disk('public')->assertMissing($path);
        Storage::disk('public')->assertExists($newPath);

        // Delete photo
        $this->service->deleteCoverPhoto($stock);
        $stock->refresh();
        $this->assertNull($stock->cover_image_path);
        Storage::disk('public')->assertMissing($newPath);
    }

    public function test_delete_stock_removes_cover_photo(): void
    {
        $user = User::factory()->create();
        $stock = Stock::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->image('cover.jpg', 600, 400);
        $path = $this->service->updateCoverPhoto($stock, $file);
        $stock->refresh();

        $this->service->deleteStock($stock);
        $this->assertDatabaseMissing('stocks', ['id' => $stock->id]);
        Storage::disk('public')->assertMissing($path);
    }
}

