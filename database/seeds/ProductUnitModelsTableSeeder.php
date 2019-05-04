<?php

use Illuminate\Database\Seeder;

class ProductUnitModelsTableSeeder extends Seeder
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
            DB::table('product_unit_models')->insert([
                'name' => 'Unit Model ' . ($i + 1),
            ]);
      	}
    }
}
