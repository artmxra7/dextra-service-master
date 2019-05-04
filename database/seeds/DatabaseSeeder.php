<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompaniesTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(NewsCategoriesTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(ProductBrandsTableSeeder::class);
        $this->call(ProductUnitModelsTableSeeder::class);
        $this->call(QuotationTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(JobCategoriesTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(JobTableSeeder::class);
    }
}
