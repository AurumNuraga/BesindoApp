<?php

namespace Database\Seeders;

use App\Models\CashAccount;
use App\Models\JournalAccount;
use App\Models\PackageCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SupplierSeeder::class,
            WarehouseSeeder::class,
            ProductCategorySeeder::class,
            PackageCategorySeeder::class,
            BrandCategorySeeder::class,
            ProductSeeder::class,
            CustomerCategorySeeder::class,
            CustomerSeeder::class,
            PurchaseCategorySeeder::class,
            SaleCategorySeeder::class,
            CashAccountSeeder::class,
            OutlayAccountSeeder::class,
            IncomeAccountSeeder::class,
            JournalAccountSeeder::class,
        ]);
    }
}
