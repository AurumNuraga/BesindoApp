<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::create([
            'name' => 'ELANG PERKASA',
            'address' => 'Surabaya',
            'telephone' => '031 1293203',
            'hp' => '',
            'npwp' => '01.234.567.8-901.000',
        ]);
    }
}