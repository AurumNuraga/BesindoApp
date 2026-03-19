<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Arif',
            'address' => 'Jl. Jendral Sudirman No. Kav 50',
            'telephone' => '021-5556789',
            'hp' => '081234567890',
            'category_id' => 1,
            'city' => 'Jakarta Selatan',
            'province' => 'DKI Jakarta',
            'postal_code' => '12190',
            'country' => 'Indonesia',
            'fax' => '021-5556788',
            'hp2' => '081398765432',
            'tax_name' => 'PT. SENTOSA ABADI',
            'information' => 'Customer VIP, pembayaran termin 30 hari.',
            'npw' => '01.234.567.8-012.000',
            'nppkp' => '01.234.567.8-012.000',
            'ekspedisi' => 'JNE Trucking',
            'account_number' => '123-456-7890 (BCA)',
            'status' => 'Active'
        ]);
    }
}