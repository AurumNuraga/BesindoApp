<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Besi Beton 10mm',
            'category_id' => '1',
            'supplier_id' => '1',
            'brand_id' => '1',
            'package_id' => '1',
            'group' => '',
            'barcode' => '',
            'tax' => '',
        ]);
    }
}