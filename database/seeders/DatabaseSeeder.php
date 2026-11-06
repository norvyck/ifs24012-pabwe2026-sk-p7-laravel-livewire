<?php

namespace Database\Seeders;

use App\Features\Stock\Models\Stock;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('admin'),
                'role' => 'admin',
            ]
        );

        $member = User::firstOrCreate(
            ['email' => 'member@example.com'],
            [
                'name' => 'Member User',
                'username' => 'member',
                'password' => Hash::make('member'),
                'role' => 'member',
            ]
        );

        Stock::firstOrCreate(
            ['user_id' => $member->id, 'name' => 'Laptop ASUS Vivobook 14'],
            [
                'sku' => 'LAP-ASUS-001',
                'description' => 'Laptop kerja spesifikasi Intel Core i5 dengan 16GB RAM dan 512GB NVMe SSD.',
                'quantity' => 12,
                'unit' => 'unit',
                'price' => 8500000,
                'min_stock' => 5,
                'status' => 'available',
            ]
        );

        Stock::firstOrCreate(
            ['user_id' => $member->id, 'name' => 'Mouse Wireless Logitech M330'],
            [
                'sku' => 'ACC-LOGI-002',
                'description' => 'Mouse silent click dengan koneksi wireless 2.4GHz dan baterai tahan hingga 18 bulan.',
                'quantity' => 3,
                'unit' => 'pcs',
                'price' => 185000,
                'min_stock' => 5,
                'status' => 'low_stock',
            ]
        );

        Stock::firstOrCreate(
            ['user_id' => $member->id, 'name' => 'Kabel HDMI 2.1 Ultra High Speed 2M'],
            [
                'sku' => 'CAB-HDMI-003',
                'description' => 'Kabel HDMI 8K 60Hz / 4K 120Hz dengan braided nylon material.',
                'quantity' => 0,
                'unit' => 'pcs',
                'price' => 95000,
                'min_stock' => 10,
                'status' => 'out_of_stock',
            ]
        );
    }
}
