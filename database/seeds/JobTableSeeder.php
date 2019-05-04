<?php

use Illuminate\Database\Seeder;

class JobTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jobs')->insert([
            'id' => 1,
            'job_category_id' => 1,
            'description' => 'Test',
            'location_name' => 'Kesatrian',
            'location_lat' => -7.430480,
            'location_long' => 112.720924,
            'location_description' => 'Masuk gang pinggir Hisana Fried Chicken',
            'status' => 'waiting',
            'user_member_id' => 3,
            'created_at' => new \DateTime
        ]);
          DB::table('jobs')->insert([
              'id' => 2,
              'job_category_id' => 2,
              'description' => 'Test lagi',
              'location_name' => 'Kesatrian',
              'location_lat' => -7.430480,
              'location_long' => 112.720924,
              'location_description' => 'Masuk gang pinggir Hisana Fried Chicken',
              'status' => 'waiting',
              'user_member_id' => 3,
              'created_at' => new \DateTime
          ]);
    }
}
