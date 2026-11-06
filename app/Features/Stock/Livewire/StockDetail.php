<?php

namespace App\Features\Stock\Livewire;

use App\Features\Stock\Enums\StockStatus;
use App\Features\Stock\Models\Stock;
use App\Features\Stock\Services\StockService;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;

class StockDetail extends Component
{
    use WithFileUploads;

    public Stock $stock;
    public string $name = '';
    public ?string $sku = '';
    public ?string $description = '';
    public int $quantity = 0;
    public string $unit = 'pcs';
    public ?float $price = null;
    public int $minStock = 5;
    public string $status = 'available';

    public mixed $coverPhoto = null;
    public bool $showEditModal = false;
    public bool $confirmingStockDeletion = false;
    public bool $confirmingCoverDeletion = false;

    public function mount(Stock $stock): void
    {
        Gate::authorize('view', $stock);

        $this->stock = $stock;
        $this->fillFormProperties();
    }

    protected function fillFormProperties(): void
    {
        $this->name = $this->stock->name;
        $this->sku = $this->stock->sku;
        $this->description = $this->stock->description ?? '';
        $this->quantity = $this->stock->quantity;
        $this->unit = $this->stock->unit;
        $this->price = $this->stock->price !== null ? (float) $this->stock->price : null;
        $this->minStock = $this->stock->min_stock;
        $this->status = $this->stock->status;
    }

    public function openEditModal(): void
    {
        Gate::authorize('update', $this->stock);

        $this->fillFormProperties();
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
    }

    public function updatedQuantity(): void
    {
        $this->status = StockStatus::fromQuantity((int) $this->quantity, (int) $this->minStock)->value;
    }

    public function updatedMinStock(): void
    {
        $this->status = StockStatus::fromQuantity((int) $this->quantity, (int) $this->minStock)->value;
    }

    public function adjustQuantity(int $delta, StockService $stockService): void
    {
        Gate::authorize('update', $this->stock);

        $stockService->adjustQuantity($this->stock, $delta);
        $this->stock->refresh();
        $this->fillFormProperties();

        session()->flash('success', 'Jumlah stok berhasil disesuaikan.');
    }

    public function updatedCoverPhoto(StockService $stockService): void
    {
        Gate::authorize('update', $this->stock);

        $this->validate([
            'coverPhoto' => ['required', 'image', 'max:5120'], // max 5MB
        ]);

        $stockService->updateCoverPhoto($this->stock, $this->coverPhoto);
        $this->stock->refresh();
        $this->reset('coverPhoto');

        session()->flash('success', 'Cover stok barang berhasil diperbarui.');
    }

    public function confirmDeleteCover(): void
    {
        $this->confirmingCoverDeletion = true;
    }

    public function cancelDeleteCover(): void
    {
        $this->confirmingCoverDeletion = false;
    }

    public function deleteCoverPhoto(StockService $stockService): void
    {
        Gate::authorize('update', $this->stock);

        $stockService->deleteCoverPhoto($this->stock);
        $this->stock->refresh();
        $this->confirmingCoverDeletion = false;

        session()->flash('success', 'Cover stok barang berhasil dihapus.');
    }

    public function saveStock(StockService $stockService): void
    {
        Gate::authorize('update', $this->stock);

        $validated = $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'quantity' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'minStock' => ['required', 'integer', 'min:0'],
        ]);

        $status = StockStatus::fromQuantity((int) $validated['quantity'], (int) $validated['minStock'])->value;

        $stockService->updateStock($this->stock, [
            'name' => $validated['name'],
            'sku' => $validated['sku'] ?: null,
            'description' => $validated['description'],
            'quantity' => (int) $validated['quantity'],
            'unit' => $validated['unit'],
            'price' => $validated['price'] !== null ? (float) $validated['price'] : null,
            'min_stock' => (int) $validated['minStock'],
            'status' => $status,
        ]);

        $this->stock->refresh();
        $this->fillFormProperties();
        $this->closeEditModal();

        session()->flash('success', 'Informasi stok barang berhasil disimpan.');
    }

    public function confirmDeleteStock(): void
    {
        $this->confirmingStockDeletion = true;
    }

    public function cancelDeleteStock(): void
    {
        $this->confirmingStockDeletion = false;
    }

    public function deleteStock(StockService $stockService): mixed
    {
        Gate::authorize('delete', $this->stock);

        $this->confirmingStockDeletion = false;
        $stockService->deleteStock($this->stock);

        session()->flash('success', 'Stok barang berhasil dihapus.');

        return redirect()->route('stocks.index');
    }

    public function render(): mixed
    {
        return view('livewire.stock.stock-detail', [
            'statuses' => StockStatus::cases(),
        ])->layout('components.layouts.app', ['title' => 'Detail Stok: ' . $this->stock->name]);
    }
}

