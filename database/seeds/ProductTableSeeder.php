<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $limit = 25;

        for ($i = 0; $i < $limit; $i++) {
            $title =  $faker->sentence(1);

            DB::table('products')->insert(
                [
                    'title' => $title,
                    'slug' => str_slug ( $title ),
                    'no_product' => 'no-product-' . $i,
                    'sn_product' => 'sn-product-' . $i,
                    'photo' => '',
                    'description' => $faker->sentence(200),
                    'price_piece' => 2000,
                    'price_box' => 12000,
                    'is_active' => 1,
                    'is_stock_available' => 1,
                    'type' => $faker->randomElement(array('pcs', 'box')),
                    'product_unit_model_id' => rand(1, 5),
                    'product_brand_id' => rand(1, 5),
                ]
            );
        }

    }
}
