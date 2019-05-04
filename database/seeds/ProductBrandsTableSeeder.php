<?php

use Illuminate\Database\Seeder;

class ProductBrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $limit = 5;

      	for ($i = 0; $i < $limit; ++$i)
      	{
            DB::table('product_brands')->insert([
                'name' => 'Brand ' . ($i + 1),
            ]);
      	}
    }
}
