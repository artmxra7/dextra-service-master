<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name' => 'Xiaomi Inc',
            'sector_business' => 'Phone',
            'user_position_title' => 'President',
            'email' => 'office@xiaomi.com',
            'photo' => '',
            'phone' => '081232323',
            'address' => 'Jln. Yang Lurus',
            'user_member_id' => 3,
            'created_at' => new \DateTime,
        ]);
    }
}
