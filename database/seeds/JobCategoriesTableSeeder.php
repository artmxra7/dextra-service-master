<?php

use Illuminate\Database\Seeder;

class JobCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        foreach (range(1, 5) as $i) {
            DB::table('job_categories')->insert([
                'name' => 'Category ' . $i,
            ]);
        }
    }
}
