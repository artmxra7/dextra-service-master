<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'key'      => 'SALES_COMMISION',
                'value'    => '5',
            ],
            [
                'key'      => 'MECHANIC_COMMISION',
                'value'    => '5',
            ],
            [
                'key'      => 'MIN_WITHDRAW',
                'value'    => '500000',
            ]
        ];

        DB::table('settings')->insert($data);
    }
}
