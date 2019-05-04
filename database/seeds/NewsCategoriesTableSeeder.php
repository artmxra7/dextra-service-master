<?php

use Illuminate\Database\Seeder;

class NewsCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('news_categories')->insert([
            [
                'id' => 1,
                'name' => 'Otomotif'
            ],
            [
                'id' => 2,
                'name' => 'Sport'
            ],
            [
                'id' => 3,
                'name' => 'Sparepart'
            ]
        ]);        
    }
}
