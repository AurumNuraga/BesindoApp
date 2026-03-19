<?php

namespace Database\Seeders;

use App\Models\JournalAccount;
use Illuminate\Database\Seeder;

class JournalAccountSeeder extends Seeder
{
    public function run(): void
    {
        JournalAccount::create([
            'code' => '1102',
            'name' => 'Bank Panin',
        ]);
        JournalAccount::create([
            'code' => '1103',
            'name' => 'Bank BCA',
        ]);
        JournalAccount::create([
            'code' => '1104',
            'name' => 'Cash',
        ]);
    }
}