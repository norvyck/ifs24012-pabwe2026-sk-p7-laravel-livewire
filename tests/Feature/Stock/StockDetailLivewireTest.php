<?php

namespace Tests\Feature\Stock;

use App\Features\Stock\Livewire\StockDetail;
use App\Features\Stock\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class StockDetailLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_stock_detail(): void
    {
        $stock = Stock::factory()->create();

        $this->get(route('stocks.show', $stock->id))
            ->assertRedirect(route('login'));
    }

    public function test_member_cannot_access_other_member_stock(): void
    {
        $owner = User::factory()->member()->create();
        $otherMember = User::factory()->member()->create();

        $stock = Stock::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherMember);

        $this->get(route('stocks.show', $stock->id))
            ->assertStatus(403);
    }

    public function test_member_can_view_own_stock_detail(): void
    {
        $user = User::factory()->member()->create();
        $stock = Stock::factory()->create([
            'user_id' => $user->id,
            'name' => 'Kertas Foto Glossy',
            'sku' => 'PHOTO-001',
            'description' => 'Kertas foto 200gr',
            'quantity' => 15,
            'unit' => 'pack',
            'price' => 35000,
            'min_stock' => 5,
            'status' => 'available',
        ]);

        $this->actingAs($user);

        Livewire::test(StockDetail::class, ['stock' => $stock])
            ->assertOk()
            ->assertSet('name', 'Kertas Foto Glossy')
            ->assertSet('sku', 'PHOTO-001')
            ->assertSet('quantity', 15)
            ->assertSet('status', 'available')
            ->assertSee('Kertas Foto Glossy');
    }

    public function test_admin_can_view_member_stock_detail(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->member()->create();
        $stock = Stock::factory()->create(['user_id' => $member->id, 'name' => 'Stok Milik Member']);

        $this->actingAs($admin);

        Livewire::test(StockDetail::class, ['stock' => $stock])
            ->assertOk()
            ->assertSee('Stok Milik Member');
    }

    public function test_open_and_close_edit_modal(): void
    {
        $user = User::factory()->member()->create();
        $stock = Stock::factory()->create([
            'user_id' => $user->id,
            'name' => 'Judul Detail',
            'description' => 'Deskripsi lama',
        ]);

        $this->actingAs($user);

        Livewire::test(StockDetail::class, ['stock' => $stock])
            ->assertSet('showEditModal', false)
            ->call('openEditModal')
            ->assertSet('showEditModal', true)
            ->assertSet('name', 'Judul Detail')
            ->call('closeEditModal')
            ->assertSet('showEditModal', false);
    }

    public function test_update_stock_validation_and_success(): void
    {
        $user = User::factory()->member()->create();
        $stock = Stock::factory()->create(['user_id' => $user->id, 'name' => 'Judul Awal', 'quantity' => 10, 'min_stock' => 5]);

        $this->actingAs($user);

        // Validation errors
        Livewire::test(StockDetail::class, ['stock' => $stock])
            ->call('openEditModal')
            ->set('name', 'ab') // min:3
            ->set('quantity', -1)
            ->call('saveStock')
            ->assertHasErrors(['name', 'quantity']);

        // Success
        Livewire::test(StockDetail::class, ['stock' => $stock])
            ->call('openEditModal')
            ->set('name', 'Judul Telah Diperbarui')
            ->set('sku', 'NEW-SKU')
            ->set('description', 'Rincian Baru')
            ->set('quantity', 2)
            ->set('unit', 'box')
            ->set('price', 45000)
            ->set('minStock', 5)
            ->call('saveStock')
            ->assertHasNoErrors()
            ->assertSet('showEditModal', false);

        $this->assertDatabaseHas('stocks', [
            'id' => $stock->id,
            'name' => 'Judul Telah Diperbarui',
            'sku' => 'NEW-SKU',
            'status' => 'low_stock',
        ]);
    }

    public function test_form_reactivity_on_quantity_and_min_stock(): void
    {
        $user = User::factory()->member()->create();
        $stock = Stock::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(StockDetail::class, ['stock' => $stock])
            ->set('minStock', 5)
            ->set('quantity', 2)
            ->assertSet('status', 'low_stock')
            ->set('quantity', 0)
            ->assertSet('status', 'out_of_stock')
            ->set('quantity', 10)
            ->assertSet('status', 'available');
    }

    public function test_adjust_quantity(): void
    {
        $user = User::factory()->member()->create();
        $stock = Stock::factory()->create([
            'user_id' => $user->id,
            'quantity' => 6,
            'min_stock' => 5,
            'status' => 'available',
        ]);

        $this->actingAs($user);

        Livewire::test(StockDetail::class, ['stock' => $stock])
            ->call('adjustQuantity', -1)
            ->assertSet('quantity', 5)
            ->assertSet('status', 'low_stock');

        $this->assertEquals(5, $stock->fresh()->quantity);
        $this->assertEquals('low_stock', $stock->fresh()->status);
    }

    public function test_cover_photo_upload_and_delete(): void
    {
        Storage::fake('public');

        $user = User::factory()->member()->create();
        $stock = Stock::factory()->create(['user_id' => $user->id, 'cover_image_path' => null]);

        $this->actingAs($user);

        // Invalid file
        $invalidFile = UploadedFile::fake()->create('document.pdf', 100);
        Livewire::test(StockDetail::class, ['stock' => $stock])
            ->set('coverPhoto', $invalidFile)
            ->assertHasErrors(['coverPhoto']);

        // Valid image upload
        $imageFile = UploadedFile::fake()->image('cover.jpg', 800, 400);
        $component = Livewire::test(StockDetail::class, ['stock' => $stock])
            ->set('coverPhoto', $imageFile)
            ->assertHasNoErrors();

        $stock->refresh();
        $this->assertNotNull($stock->cover_image_path);
        Storage::disk('public')->assertExists($stock->cover_image_path);

        // Delete cover photo
        $component->call('deleteCoverPhoto');

        $stock->refresh();
        $this->assertNull($stock->cover_image_path);
    }

    public function test_delete_stock_redirects(): void
    {
        $user = User::factory()->member()->create();
        $stock = Stock::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(StockDetail::class, ['stock' => $stock])
            ->call('deleteStock')
            ->assertRedirect(route('stocks.index'));

        $this->assertDatabaseMissing('stocks', ['id' => $stock->id]);
    }

    public function test_confirm_and_cancel_delete_modal_actions(): void
    {
        $user = User::factory()->member()->create();
        $stock = Stock::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(StockDetail::class, ['stock' => $stock])
            ->assertSet('confirmingStockDeletion', false)
            ->call('confirmDeleteStock')
            ->assertSet('confirmingStockDeletion', true)
            ->call('cancelDeleteStock')
            ->assertSet('confirmingStockDeletion', false)
            ->assertSet('confirmingCoverDeletion', false)
            ->call('confirmDeleteCover')
            ->assertSet('confirmingCoverDeletion', true)
            ->call('cancelDeleteCover')
            ->assertSet('confirmingCoverDeletion', false);
    }
}

