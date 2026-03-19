<?php

namespace Database\Seeders;

use App\Models\CashAccount;
use Illuminate\Database\Seeder;

class CashAccountSeeder extends Seeder
{
    public function run(): void
    {
        CashAccount::create([
            'name' => 'Cash',
        ]);
        CashAccount::create([
            'name' => 'Bank BCA',
        ]);
    }
}