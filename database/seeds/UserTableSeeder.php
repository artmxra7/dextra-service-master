<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@dextratama.com',
            'password' => bcrypt('test'),
            'role' => 'admin',
            'created_at' => new \DateTime
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'name' => 'Sales',
            'email' => 'sales@dextratama.com',
            'password' => bcrypt('test'),
            'role' => 'sales',
            'created_at' => new \DateTime
        ]);
        DB::table('users')->insert([
            'id' => 3,
            'name' => 'Member',
            'email' => 'member@dextratama.com',
            'password' => bcrypt('test'),
            'role' => 'member',
            'created_at' => new \DateTime,
            'user_sales_id' => 2,
            'company_id' => 1, 
        ]);
        DB::table('users')->insert([
            'id' => 4,
            'name' => 'Mekanik',
            'email' => 'mekanik@dextratama.com',
            'password' => bcrypt('test'),
            'role' => 'mechanic',
            'created_at' => new \DateTime,
        ]);
    }
}
