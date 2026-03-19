<?php

namespace Database\Seeders;

use App\Models\SaleCategory;
use Illuminate\Database\Seeder;

class SaleCategorySeeder extends Seeder
{
    public function run(): void
    {
        SaleCategory::create(
        [
            'name' => 'Cash',
        ],);
        SaleCategory::create(
        [
            'name' => 'Credit',
        ],);

    }
}