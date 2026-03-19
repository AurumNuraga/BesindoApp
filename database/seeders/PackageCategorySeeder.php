<?php

namespace Database\Seeders;

use App\Models\PackageCategory;
use Illuminate\Database\Seeder;

class PackageCategorySeeder extends Seeder
{
    public function run(): void
    {
        PackageCategory::create([
            'name' => 'Batang',
        ]);
    }
}