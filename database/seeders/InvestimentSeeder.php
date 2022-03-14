<?php

namespace Database\Seeders;

use App\Models\Investiment;
use Illuminate\Database\Seeder;

class InvestimentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Investiment::factory()->count(2)->create();
    }
}
