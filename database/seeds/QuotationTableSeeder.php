<?php

use Illuminate\Database\Seeder;

class QuotationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('quotations')->insert([
			'file' => 'string',
			'remarks' => 'Jadi',
			'price' => 100000,
			'job_id' => 1
		]);
    }
}
