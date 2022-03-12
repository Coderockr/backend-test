<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvestmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // populating investments database
        DB::table('investments')->insert([
            ['owner' => '1',
            'amount' => '1000',
            'create_date' => '2022-03-10'],
            ['owner' => '1',
            'amount' => '5000',
            'create_date' => '2012-03-10'],
            ['owner' => '2',
            'amount' => '900',
            'create_date' => '2020-03-10'],
            ['owner' => '2',
            'amount' => '300',
            'create_date' => '2021-05-03'],
            ['owner' => '3',
            'amount' => '3000',
            'create_date' => '2021-01-25'],
            ['owner' => '3',
            'amount' => '2000',
            'create_date' => '2021-06-25'],
            ['owner' => '3',
            'amount' => '5000',
            'create_date' => '2022-01-25']
        ]);
    }
}
