<?php

namespace Database\Seeders;

use App\Models\Investment;
use App\Models\Investor;
use Illuminate\Database\Seeder;

class InvestorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Investor::factory()
            ->count(10)
            ->has(Investment::factory()->count(1), 'investments')
            ->create();
    }
}
