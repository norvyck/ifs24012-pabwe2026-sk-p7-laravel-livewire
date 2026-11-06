<?php

namespace Tests\Unit\DTO;

use App\Features\Ai\DTO\GeneratedStockItem;
use PHPUnit\Framework\TestCase;

class GeneratedStockItemTest extends TestCase
{
    public function test_dto_properties_and_to_array(): void
    {
        $dto = new GeneratedStockItem(
            name: 'Kertas HVS A4',
            sku: 'HVS-001',
            description: 'Kertas HVS 80gr',
            quantity: 20,
            unit: 'rim',
            price: 55000.0,
            minStock: 5,
            status: 'available'
        );

        $this->assertEquals('Kertas HVS A4', $dto->name);
        $this->assertEquals('HVS-001', $dto->sku);
        $this->assertEquals('Kertas HVS 80gr', $dto->description);
        $this->assertEquals(20, $dto->quantity);
        $this->assertEquals('rim', $dto->unit);
        $this->assertEquals(55000.0, $dto->price);
        $this->assertEquals(5, $dto->minStock);
        $this->assertEquals('available', $dto->status);

        $array = $dto->toArray();
        $this->assertEquals([
            'name' => 'Kertas HVS A4',
            'sku' => 'HVS-001',
            'description' => 'Kertas HVS 80gr',
            'quantity' => 20,
            'unit' => 'rim',
            'price' => 55000.0,
            'min_stock' => 5,
            'status' => 'available',
        ], $array);
    }
}

