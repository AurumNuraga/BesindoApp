<?php

namespace Database\Seeders;

use App\Models\OutlayAccount;
use Illuminate\Database\Seeder;

class OutlayAccountSeeder extends Seeder
{
    public function run(): void
    {
        OutlayAccount::create([
            'name' => 'Beban Solar',
        ]);
    }
}