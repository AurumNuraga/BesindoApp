<?php

namespace Database\Seeders;

use App\Models\BrandCategory;
use Illuminate\Database\Seeder;

class BrandCategorySeeder extends Seeder
{
    public function run(): void
    {
        BrandCategory::create([
            'name' => 'BTG',
        ]);
    }
}