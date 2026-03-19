<?php

namespace Database\Seeders;

use App\Models\PurchaseCategory;
use Illuminate\Database\Seeder;

class PurchaseCategorySeeder extends Seeder
{
    public function run(): void
    {
        PurchaseCategory::create([
            'name' => 'Cash',
        ]);
    }
}