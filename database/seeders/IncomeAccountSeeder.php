<?php

namespace Database\Seeders;

use App\Models\IncomeAccount;
use Illuminate\Database\Seeder;

class IncomeAccountSeeder extends Seeder
{
    public function run(): void
    {
        IncomeAccount::create([
            'name' => 'Bunga Bank Panin',
        ]);
    }
}