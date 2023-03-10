<?php

namespace Database\Seeders;

use App\Models\Investment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Investment::factory()
            ->count(2)
            ->create();
    }
}
