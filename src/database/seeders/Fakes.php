<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Fakes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\AdminUser::factory(5)->create()->each(function ($investor) {});
        \App\Models\InvestorUser::factory(20)->create()->each(function ($investor) {});
        \App\Models\Investment::factory(100)->create()->each(function ($investor) {});
    }
}
