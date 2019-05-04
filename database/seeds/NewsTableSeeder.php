<?php

use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $limit = 200;
        $date = new \DateTime();

        for ($i = 0; $i < $limit; $i++) {
            $title =  $faker->sentence(1);

            DB::table('news')->insert(
                [
                    'title' => $title,
                    'slug' => str_slug($title),
                    'photo' => '',
                    'content' => $faker->sentence(200),
                    'news_category_id' => rand(1, 3),
                    'created_at' => $date->format('Y-m-d H:i:s'),
                    'updated_at' => $date->format('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
